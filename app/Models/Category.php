<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        "category"
    ];

    public static function getTopRatedBooksByCategory($categoryId, $limit = 10)
    {
        $category = Category::find($categoryId);

        if (!$category) {
            return [];
        }

        return $category->books()
            ->orderByDesc('rating')
            ->limit($limit)
            ->get();
    }

    public function book()
    {
        return $this->belongsToMany(Book::class, "book_category");
    }
}
