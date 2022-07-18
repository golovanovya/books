<?php

namespace App\Jobs;

use App\Actions\Book\ParseXml;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

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

    public function handle(ParseXml $parseXml): void
    {
        $parseXml($this->filepath);
    }
}
