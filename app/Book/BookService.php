<?php

namespace App\Book;

use App\Models\Book;
use Illuminate\Support\Facades\DB;

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
    public function create(BookCreateDto $dto): Book
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
