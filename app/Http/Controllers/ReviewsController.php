<?php
namespace App\Http\Controllers;

use App\Http\Requests\ReviewRequest;
use App\Models\Book;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewsController extends Controller
{
    public function store(ReviewRequest $request, Book $book)
    {
        $validatedData = $request->validated();

        $bookId = $book["id"];
        $userId = Auth::id();

        if (!$book->exists) {
            return response()->json([
                "status" => "error",
                "message" => "Book not found"
            ], 404);
        }

        $user = Auth::user(); // Get the authenticated user

        $review = Review::create([
            "title" => $validatedData["title"],
            "comment" => $validatedData["comment"],
            "rating" => $validatedData["rating"],
            "book_id" => $bookId,
            "user_id" => $userId
        ]);

        $review->load('user'); 

        return response()->json([
            "status" => "success",
            "reviews" => [
                $review->toArray()
            ],
            "user" => $user // Include the whole user in the response
        ]);
    }
}
