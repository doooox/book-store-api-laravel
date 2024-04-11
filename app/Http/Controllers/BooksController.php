<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddBookRequest;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;

class BooksController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->query("search");
        $query = Book::with('categories', "reviews");
        if ($search) {
            $query = $query->where(function ($query) use ($search) {
                $query->where("title", "like", "%$search%")
                    ->orWhere("author", "like", "%$search%");
            });
        }

        $books = $query->orderBy("created_at", "desc")->paginate(16);

        return response()->json([
            "status" => "success",
            "books" => $books,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AddBookRequest $request)
    {
        $validatedData = $request->validated();

        $newImageName = uniqid() . '-' . $request->photo->getClientOriginalName() . '.' . $request->photo->extension();
        $request->photo->move(public_path('images'), $newImageName);

        $book = Book::create([
            "title" => $validatedData["title"],
            "author" => $validatedData["author"],
            "release_date" => $validatedData["release_date"],
            "description" => $validatedData["description"],
            "photo" => $newImageName,
            "amount" => $validatedData["amount"],
            "format" => $validatedData["format"],
            "pages" => $validatedData["pages"],
            "price" => $validatedData["price"]
        ]);

        $categories = collect($validatedData["categories"])->map(function ($category) {
            return Category::firstOrCreate(["category" => $category]);
        });

        $book->categories()->sync($categories->pluck("id"));

        return response()->json([
            "status" => "success",
            "book" => $book
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $book = Book::with("categories", "reviews", "reviews.user")->findOrFail($id);

        $rating = $book->averageRating();

        return response()->json([
            "status" => "success",
            "book" => $book,
            "rating" => $rating
        ]);
    }

    public function filteredTopRatedBooks(string $category)
    {
        $filteredBooks = Book::whereHas('categories', function ($query) use ($category) {
            $query->where('category', $category);
        })
            ->with("categories")
            ->withAvg('reviews', 'rating')
            ->orderByDesc('reviews_avg_rating')
            ->limit(10)
            ->get();

        return response()->json([
            'status' => 'success',
            'filteredBooks' => $filteredBooks
        ]);
    }

    public function getAuthors($author)
    {
        $bookAuthor = Book::where('author', $author)->paginate(15);

        if ($bookAuthor->isEmpty()) return response()->json([
            "status" => "fail",
            "response" => "No books found"
        ]);

        return response()->json([
            'status' => "sucess",
            'books' => $bookAuthor
        ]);
    }

    public function getCategory($category)
    {
        $bookCategory = Book::whereHas('categories', function ($query) use ($category) {
            $query->where('category', $category);
        })
            ->with("categories")
            ->orderByDesc("created_at")
            ->get();

        return response()->json([
            'status' => 'success',
            'books' => $bookCategory
        ]);
    }

    public function getByPriceDesc()
    {
        $book = Book::orderByDesc("price")->get();

        return response()->json([
            "status"=> "success",
            "book"=> $book
        ]);

    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
