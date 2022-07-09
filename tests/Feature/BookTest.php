<?php

namespace Tests\Feature;

use App\Book\BatchXmlJob;
use App\Book\BookCreateDto;
use App\Book\BookJob;
use App\Book\BookService;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BookTest extends TestCase
{
    use RefreshDatabase;

    private function initFakes()
    {
        Storage::fake('public');
        Storage::fake('local');
        Queue::fake();
    }
    public function testUpload()
    {
        $this->initFakes();

        Queue::assertNothingPushed(BatchXmlJob::class);
        $file = UploadedFile::fake()->create('books.xml');
        $response = $this->post('/api/books/upload', ['file' => $file]);

        $response->assertStatus(200)
            ->assertJsonPath('message', 'File successfully uploaded.');
        Storage::disk('local')
            ->assertExists('documents/' . $file->hashName());

        Queue::assertPushed(BatchXmlJob::class);
    }

    public function testFailedUpload()
    {
        $response = $this->post('/api/books/upload');
        $response->assertStatus(401)
            ->assertJsonPath('error.file.0', 'The file field is required.');
    }

    public function testXmlJob()
    {
        $this->initFakes();

        Queue::assertNothingPushed(BookJob::class);

        $filepath = Storage::disk('test')->path('books.xml');
        (new BatchXmlJob($filepath))->handle();

        Queue::assertPushed(BookJob::class, 2);
    }

    public function testCreate()
    {
        $this->initFakes();
        /** @var Book $book */
        $book = app()->get(BookService::class)
            ->create(BookCreateDto::createFromXml([
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

        $response = $this->get('/api/books');
        $response->assertJsonPath('data.0.isbn', $book->isbn);
        $response->assertJsonPath('meta.total', 1);
    }

    public function testGetBooks()
    {
        $book = Book::factory(150)->create();
        $this->assertDatabaseCount('books', 150);
        $response = $this->get('/api/books');
        $response->assertStatus(200)
            ->assertJsonPath('meta.total', 150)
            ->assertJsonPath('meta.per_page', 100)
            ->assertJsonCount(100, 'data');
    }
}
