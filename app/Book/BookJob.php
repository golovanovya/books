<?php

namespace App\Book;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Job for saving book
 */
class BookJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    private $bookDto;

    public function __construct(BookCreateDto $bookDto)
    {
        $this->bookDto = $bookDto;
    }

    /**
     * @inheritDoc
     *
     * @param BookService $bookService
     * @return void
     */
    public function handle(
        BookService $bookService
    ) {
        try {
            $bookService->create($this->bookDto);
        } catch (QueryException $e) {
            if ($e->getCode() != 23000) {
                throw $e;
            }
        }
    }
}
