<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Books\BookRentalFormRequest;
use App\Http\Requests\Books\BookReturnFormRequest;
use App\Http\Requests\Books\DeleteBookRequest;
use App\Http\Requests\Books\StoreBookRequest;
use App\Http\Requests\Books\UpdateBookRequest;
use App\Http\Requests\Books\ViewBookRequest;
use App\Http\Requests\Books\ViewRentedBookListRequest;
use App\Models\BookRental;
use App\Services\BookService;

class BookController extends Controller
{
    protected $bookService;

    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }

    public function index(ViewBookRequest $request)
    {
        $perPage = $request->input('per_page', 10);
        $books = $this->bookService->getAllBooks($perPage);

        $response = [
            'data' => $books->items(),
            'meta' => [
                'current_page' => $books->currentPage(),
                'limit' => $books->perPage(),
                'total_pages' => $books->lastPage(),
                'total' => $books->total(),
            ],
        ];

        return response()->json($response);
    }

    public function store(StoreBookRequest $request)
    {
        $book = $this->bookService->createBook($request->validated());
        return response()->json([
            'message' => 'Book created successfully',
            'book' => $book
        ], 201);
    }

    public function show(ViewBookRequest $request, $id)
    {
        $book = $this->bookService->getBookById($id);

        if (is_null($book)) {
            return response()->json(['message' => 'Book not found'], 404);
        }

        return response()->json($book);
    }

    public function update(UpdateBookRequest $request, $id)
    {
        $book = $this->bookService->updateBook($id, $request->validated());

        if (is_null($book)) {
            return response()->json(['message' => 'Book not found'], 404);
        }

        return response()->json([
            'message' => 'Book updated successfully',
            'book' => $book
        ], 200);
    }

    public function destroy(DeleteBookRequest $request, $id)
    {
        $deleted = $this->bookService->deleteBook($id);

        if (!$deleted) {
            return response()->json(['message' => 'Book not found'], 404);
        }

        return response()->json(['message' => 'Book deleted successfully'], 200);
    }

    public function rentBook(BookRentalFormRequest $request)
    {
        $bookId = $request->input('book_id');
        $userId = $request->input('user_id');

        $rental = BookRental::create([
            'book_id' => $bookId,
            'user_id' => $userId,
            'status' => 'rented'
        ]);

        return response()->json([
            'message' => 'Book rented successfully',
            'rental' => $rental
        ], 201);
    }

    public function returnBook(BookReturnFormRequest $request)
    {
        $rentalId = $request->input('rental_id');

        $rental = BookRental::find($rentalId);

        if ($rental->status !== 'rented') {
            return response()->json(['message' => 'Book is not currently rented'], 400);
        }

        $rental->update(['status' => 'returned']);

        return response()->json([
            'message' => 'Book returned successfully',
            'rental' => $rental
        ], 200);
    }

    public function listRentedBooks(ViewRentedBookListRequest $request)
{
    $perPage = $request->input('per_page', 10);
    $status = $request->input('status', 'rented');

    if (!in_array($status, ['rented', 'returned'])) {
        return response()->json(['message' => 'Invalid status'], 400);
    }

    $rentals = BookRental::with('book', 'user')
        ->where('status', $status)
        ->paginate($perPage);

    return response()->json([
        'data' => $rentals->items(),
        'meta' => [
            'current_page' => $rentals->currentPage(),
            'limit' => $rentals->perPage(),
            'total_pages' => $rentals->lastPage(),
            'total' => $rentals->total(),
        ],
    ], 200);
}

}
