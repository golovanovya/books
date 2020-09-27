<?php

namespace App\Book;

use Illuminate\Support\ServiceProvider;

class BookServiceProvider extends ServiceProvider
{
    public $singletons = [
        BookService::class => BookService::class,
        ImageUploader::class => ImageUploader::class,
    ];

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
