<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;
    protected $fillable = [
        "title",
        "author",
        "release_date",
        "description",
        "photo",
        "amount",
        "format",
        "pages",
        "price",
        "categories"
    ];
    protected $casts = [
        'categories' => 'array',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, "book_category");
    }

    public static function getAll()
    {
        return self::all();
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function averageRating()
    {
        return $this->reviews()->avg('rating');
    }
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }
}
