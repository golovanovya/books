<?php

namespace App\Book;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;

/**
 * Job for parsing xml file
 */
class BatchXmlJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    private $filepath;

    public function __construct(string $filepath)
    {
        $this->filepath = $filepath;
    }

    public function handle()
    {
        $reader = XmlParser::createFromPath($this->filepath);
        /** @var array $book */
        foreach ($reader->read() as $book) {
            $dto = BookCreateDto::createFromXml($book);
            Queue::push(new BookJob($dto));
        }
        Storage::delete($this->filepath);
    }
}
