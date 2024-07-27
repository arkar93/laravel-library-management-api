<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Books\BookSearchRequest;
use App\Models\Book;

class BookSearchController extends Controller
{
    public function search(BookSearchRequest $request)
    {
        $query = $request->input('query');
        $booksQuery = Book::query();

        if ($query) {
            $booksQuery->where('title', 'LIKE', "%{$query}%")
                ->orWhere('author', 'LIKE', "%{$query}%")
                ->orWhereHas('category', function ($q) use ($query) {
                    $q->where('name', 'LIKE', "%{$query}%");
                });
        }

        $books = $booksQuery->with('category')->get();

        return response()->json($books);
    }
}
