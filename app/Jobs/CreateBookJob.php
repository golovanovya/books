<?php

namespace App\Jobs;

use App\Actions\Book\Create;
use App\Dto\CreateBookDto;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Job for saving book
 */
class CreateBookJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    private $bookDto;

    public function __construct(CreateBookDto $bookDto)
    {
        $this->bookDto = $bookDto;
    }

    public function handle(Create $create): void
    {
        try {
            $create($this->bookDto);
        } catch (QueryException $e) {
            if ($e->getCode() != 23000) {
                throw $e;
            }
        }
    }
}
