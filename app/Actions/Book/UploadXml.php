<?php

namespace App\Actions\Book;

use App\Jobs\BatchXmlJob;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;

class UploadXml
{
    public function __invoke(UploadedFile $file): void
    {
        $filepath = $file->store('documents');
        Queue::push(new BatchXmlJob($filepath));
    }
}
