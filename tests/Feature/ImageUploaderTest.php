<?php

namespace Tests\Feature;

use App\Utils\ImageUploader;
use Exception;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Mockery\MockInterface;
use Tests\TestCase;

class ImageUploaderTest extends TestCase
{
    public function testUpload(): void
    {
        $disk = 'public';
        Storage::fake($disk);
        $remotepath = Storage::disk('test')->path('cover.png');
        $savedpath = (new ImageUploader())->upload($remotepath, $disk);
        Storage::disk($disk)->assertExists($savedpath);
    }

    public function testUploadException(): void
    {
        $disk = 'public';
        Storage::fake($disk);
        $filesystem = $this->createMock(Filesystem::class);
        $filesystem->method('put')
            ->willReturn(false);
        Storage::set($disk, $filesystem);
        $remotepath = Storage::disk('test')->path('cover.png');
        $this->expectException(Exception::class);
        (new ImageUploader())->upload($remotepath, $disk);
    }
}
