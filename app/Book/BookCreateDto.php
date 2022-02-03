<?php

namespace App\Book;

/**
 * Book create DTO
 * @property string $title
 * @property string $image
 * @property string $isbn
 * @property string $description
 */
class BookCreateDto
{
    private $title;
    private $image;
    private $isbn;
    private $description;

    public function __construct(
        string $isbn,
        string $title,
        ?string $descripton = null,
        ?string $image = null
    ) {
        $this->title = $title;
        $this->isbn = $isbn;
        $this->description = $descripton;
        $this->image = $image;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function getIsbn(): string
    {
        return $this->isbn;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Create DTO from xml parsed array
     *
     * @param array $book
     * @return self
     */
    public static function createFromXml(array $book): self
    {
        return new self(
            $book['isbn'],
            $book['title'],
            $book['description'] ?? null,
            $book['image'] ?? null,
        );
    }
}
