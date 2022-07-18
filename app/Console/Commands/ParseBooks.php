<?php

namespace App\Console\Commands;

use App\Actions\Book\ParseXml;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Queue;

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

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(ParseXml $parseXml)
    {
        $filepath = $this->argument('filepath');
        $this->line("Parsing file $filepath");
        $parseXml($filepath);
        return 0;
    }
}
