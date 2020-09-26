<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use XMLReader;

class ParseBooks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'book:parse {filepath}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import books from xml file';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    private function read(string $filepath)
    {
        $reader = new XMLReader();
        try {
            $reader->open($filepath);
            $book = [];
            while ($reader->read()) {
                if ($reader->nodeType === XMLReader::END_ELEMENT) {
                    if ($reader->name === 'book') {
                        yield $book;
                    }
                    continue;
                }
                switch ($reader->name) {
                    case 'book':
                        $book = [
                            'isbn' => $reader->getAttribute('isbn'),
                            'title' => $reader->getAttribute('title'),
                        ];
                        break;
                    case 'image':
                        $book['image'] = $reader->readString();
                        break;
                    case 'description':
                        $book['description'] = $reader->readString();
                        break;
                }
            }
        } finally {
            $reader->close();
        }
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $filepath = $this->argument('filepath');
        $this->line("Parsing file $filepath");
        $books = $this->read($filepath);
        $i = 0;
        foreach ($books as $book) {
            var_dump($book);
            if (++$i >= 10) {
                break;
            }
        }
        return 0;
    }
}
