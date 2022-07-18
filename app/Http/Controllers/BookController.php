<?php

namespace App\Http\Controllers;

use App\Book\BatchXmlJob;
use App\Http\Resources\Books;
use App\Models\Book;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

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

    public function upload(Request $request)
    {
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
    }
}
