<?php

namespace App\Actions\Book;

use App\Utils\XmlParser;
use App\Dto\CreateBookDto;
use App\Jobs\CreateBookJob;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;

class ParseXml
{
    public function __invoke(string $filepath): void
    {
        $reader = XmlParser::createFromPath($filepath);
        /** @var array $book */
        foreach ($reader->read() as $book) {
            try {
                $dto = CreateBookDto::createFromXml($book);
                Queue::push(new CreateBookJob($dto));
            } catch (\Throwable $e) {
                Log::error("Error processing book item", ['item' => $book, 'error' => $e->getTrace()]);
            }
        }
        Storage::delete($filepath);
    }
}
