<?php

namespace App\Http\Controllers;

use App\Actions\Book\UploadXml;
use App\Http\Requests\BooksUploadRequest;
use App\Http\Resources\Books;
use App\Models\Book;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class BookController extends Controller
{
    use ValidatesRequests;

    public function index(Request $request)
    {
        $search = $request->get('search');
        return new Books(
            Book::where('title', 'like', "%$search%")
                ->orWhere('isbn', 'like', "%$search%")
                ->paginate(100)
        );
    }

    public function upload(BooksUploadRequest $request, UploadXml $uploadXml)
    {
        $uploadXml($request->file);

        return response()->json([
            "success" => true,
            "message" => "File successfully uploaded.",
        ]);
    }
}
