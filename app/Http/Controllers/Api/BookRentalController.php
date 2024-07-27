<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Books\BookRentalRequest;
use App\Http\Requests\Books\ViewRentedBookRequest;
use Illuminate\Http\Request;
use App\Models\BookRental;
use App\Models\Book;
use Illuminate\Support\Facades\Auth;

class BookRentalController extends Controller
{
    public function listRentedBooks(ViewRentedBookRequest $request)
    {
        $user = Auth::user();
        
        $status = $request->input('status', 'rented');
        $perPage = $request->input('per_page', 10);

        $query = BookRental::with('book')
            ->where('user_id', $user->id)
            ->where('status', $status);

        if ($request->has('per_page')) {
            $rentals = $query->paginate($perPage);
            $response = [
                'data' => $rentals->items(),
                'meta' => [
                    'current_page' => $rentals->currentPage(),
                    'limit' => $rentals->perPage(),
                    'total_pages' => $rentals->lastPage(),
                    'total' => $rentals->total(),
                ],
            ];
        } else {
            $rentals = $query->get();
            $response = $rentals;
        }

        return response()->json($response, 200);
    }


    public function rentBook(BookRentalRequest $request)
    {

        $user = Auth::user();
        $bookId = $request->input('book_id');

        $existingRental = BookRental::where('book_id', $bookId)
            ->where('user_id', $user->id)
            ->where('status', 'rented')
            ->first();

        if ($existingRental) {
            return response()->json(['message' => 'You have already rented this book.'], 400);
        }

        $rental = BookRental::create([
            'book_id' => $bookId,
            'user_id' => $user->id,
            'status' => 'rented',
        ]);

        return response()->json($rental, 201);
    }

    public function returnBook(BookRentalRequest $request)
    {

        $user = Auth::user();
        $bookId = $request->input('book_id');

        $rental = BookRental::where('book_id', $bookId)
            ->where('user_id', $user->id)
            ->where('status', 'rented')
            ->first();

        if (!$rental) {
            return response()->json(['message' => 'You have not rented this book.'], 400);
        }

        $rental->update(['status' => 'returned']);

        return response()->json($rental, 200);
    }
}

