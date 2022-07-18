<?php

namespace App\Actions\Book;

use App\Utils\ImageUploader;
use App\Dto\CreateBookDto;
use App\Models\Book;
use Illuminate\Support\Facades\DB;

class Create
{
    private ImageUploader $imageUploader;

    public function __construct(ImageUploader $imageUploader)
    {
        $this->imageUploader = $imageUploader;
    }

    public function __invoke(CreateBookDto $dto): Book
    {
        $book = Book::create([
            'isbn' => $dto->getIsbn(),
            'title' => $dto->getTitle(),
            'description' => $dto->getDescription(),
        ]);

        if ($dto->getImage() !== null) {
            DB::transaction(function () use ($dto, $book) {
                $book->cover = $this->imageUploader->upload($dto->getImage());
                $book->storage = 'public';
            });
        }

        $book->saveOrFail();
        return $book;
    }
}
