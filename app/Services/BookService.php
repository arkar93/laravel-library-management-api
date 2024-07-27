<?php
namespace App\Services;

use App\Models\Book;

class BookService
{
    public function getAllBooks($perPage = 10)
    {
        return Book::with('category')->paginate($perPage);
    }

    public function createBook($data)
    {
        return Book::create($data);
    }

    public function getBookById($id)
    {
        return Book::with('category')->find($id);
    }

    public function updateBook($id, $data)
    {
        $book = Book::find($id);

        if (is_null($book)) {
            return null;
        }

        $book->update($data);
        return $book;
    }

    public function deleteBook($id)
    {
        $book = Book::find($id);

        if (is_null($book)) {
            return false;
        }

        $book->delete();
        return true;
    }
}
