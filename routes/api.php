<?php

use App\Book\BatchXmlJob;
use App\Http\Resources\Books;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/books', function (Request $request) {
    $search = $request->get('search');
    return new Books(
        Book::where('title', 'like', "%$search%")
            ->orWhere('isbn', 'like', "%$search%")
            ->paginate(100)
    );
});

Route::post('/books/upload', function (Request $request) {
    $validator = Validator::make($request->all(), [
        'file' => 'required|mimes:xml',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'error' => $validator->errors(),
        ], 401);
    }

    $filepath = $request->file->store('documents');
    Queue::push(new BatchXmlJob(Storage::path($filepath)));

    return response()->json([
        "success" => true,
        "message" => "File successfully uploaded.",
    ]);
});
