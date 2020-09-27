<?php

namespace App\Book;

use App\Models\Book;

/**
 * Book service
 */
class BookService
{
    private $imageUploader;

    public function __construct(ImageUploader $imageUploader)
    {
        $this->imageUploader = $imageUploader;
    }
    /**
     * Create book
     *
     * @param BookCreateDto $dto
     * @return mixed
     */
    public function create(BookCreateDto $dto)
    {
        $book = Book::create([
            'title' => $dto->getTitle(),
            'isbn' => $dto->getIsbn(),
            'description' => $dto->getDescription(),
        ]);
        if ($dto->getImage() !== null) {
            $book->image = $this->imageUploader->upload($dto->getImage());
        }
        $book->saveOrFail();
    }
}
