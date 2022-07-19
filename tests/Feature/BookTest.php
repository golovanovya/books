<?php

namespace Tests\Feature;

use App\Actions\Book\Create;
use App\Actions\Book\ParseXml;
use App\Dto\CreateBookDto;
use App\Jobs\BatchXmlJob;
use App\Jobs\CreateBookJob;
use App\Models\Book;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BookTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        Storage::fake('local');
        Queue::fake();
    }

    public function testUpload()
    {
        Queue::assertNothingPushed(BatchXmlJob::class);
        $file = UploadedFile::fake()->create('books.xml');
        $response = $this->postJson('/api/books/upload', ['file' => $file]);

        $response->assertStatus(200)
            ->assertJsonPath('message', 'File successfully uploaded.');
        Storage::disk('local')
            ->assertExists('documents/' . $file->hashName());

        Queue::assertPushed(BatchXmlJob::class);
    }

    public function testParseBookCommand()
    {
        $filepath = Storage::disk('test')->path('books.xml');
        $this->artisan("book:parse {$filepath}")->assertSuccessful();

        Queue::assertPushed(CreateBookJob::class, 2);
    }

    public function testParseWrongBookCommand()
    {
        Log::shouldReceive('error')
            ->once()
            ->withArgs(function ($message) {
                return strpos($message, 'Error processing book item') !== false;
            });
        $filepath = Storage::disk('test')->path('books_wrong.xml');
        $this->artisan("book:parse {$filepath}")->assertSuccessful();

        Queue::assertPushed(CreateBookJob::class, 1);
    }

    public function testInvalidPath()
    {
        $this->artisan('book:parse testtest.xml')->assertFailed();
    }

    public function testFailedUpload()
    {
        $response = $this->postJson('/api/books/upload');
        $response->assertStatus(422)
            ->assertJsonPath('errors.file.0', 'The file field is required.');
    }

    public function testXmlJob()
    {
        Queue::assertNothingPushed(CreateBookJob::class);

        $filepath = Storage::disk('test')->path('books.xml');
        (new BatchXmlJob($filepath))->handle(app()->get(ParseXml::class));

        Queue::assertPushed(CreateBookJob::class, 2);
    }

    public function testCreateBookJob()
    {
        $this->assertDatabaseCount('books', 0);

        $dto = CreateBookDto::createFromXml([
            'isbn' => fake()->isbn13(),
            'title' => fake()->words(2, true),
            'description' => fake()->sentence(),
            'image' => null,
        ]);
        (new CreateBookJob($dto))
            ->handle(app()->get(Create::class));

        $this->assertDatabaseCount('books', 1);
        $this->assertDatabaseHas('books', ['isbn' => $dto->getIsbn()]);

        // Ignore repeats
        (new CreateBookJob($dto))
            ->handle(app()->get(Create::class));

        $this->assertDatabaseCount('books', 1);
        $this->assertDatabaseHas('books', ['isbn' => $dto->getIsbn()]);
    }

    public function testCreateBookJobFail()
    {
        $this->assertDatabaseCount('books', 0);

        $dto = CreateBookDto::createFromXml([
            'isbn' => fake()->isbn13(),
            'title' => fake()->words(2, true),
            'description' => fake()->sentence(),
            'image' => null,
        ]);
        $createAction = $this->createMock(Create::class);
        $createAction->method('__invoke')
            ->will($this->throwException(new QueryException('some sql', [], new \Exception('test exception'))));
        $this->expectException(QueryException::class);

        (new CreateBookJob($dto))
            ->handle($createAction);
        $this->assertDatabaseCount('books', 0);
    }

    public function testCreate()
    {
        /** @var Book $book */
        $book = app()->get(Create::class)(CreateBookDto::createFromXml([
            'isbn' => '354-2-50-082770-8',
            'title' => 'Dolores voluptatum et voluptatibus architecto. Esse sapiente magni consectetur consequatur. Vel omnis ea dicta aut qui. Omnis fugit nam ex quaerat.',
            'description' => 'It sounded an excellent opportunity for making her escape; so she set to work very diligently to write out a history of the hall; but, alas! either the locks were too large, or the key was lying.',
            'image' => 'https://dummyimage.com/2831x679',
        ]));
        $this->assertDatabaseCount('books', 1);
        $this->assertDatabaseHas('books', [
            'isbn' => $book->isbn,
        ]);

        Storage::disk($book->storage)
            ->assertExists($book->cover);

        $response = $this->getJson('/api/books');
        $response->assertJsonPath('data.0.isbn', $book->isbn);
        $response->assertJsonPath('meta.total', 1);
    }

    public function testGetBooks()
    {
        Book::factory(150)->create();
        $this->assertDatabaseCount('books', 150);
        $response = $this->getJson('/api/books');
        $response->assertStatus(200)
            ->assertJsonPath('meta.total', 150)
            ->assertJsonPath('meta.per_page', 100)
            ->assertJsonCount(100, 'data');
    }
}
