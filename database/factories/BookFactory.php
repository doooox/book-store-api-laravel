<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categoryId = Category::pluck("id")->toArray();
        return [
            'title' => $this->faker->sentence(),
            'author' => $this->faker->name(),
            'release_date' => $this->faker->date(),
            'description' => $this->faker->paragraph(),
            'photo' => $this->faker->imageUrl(),
            'amount' => $this->faker->randomNumber(),
            'format' => $this->faker->randomElement(['hardcover', 'paperback', 'ebook']),
            'pages' => $this->faker->randomNumber(),
            'price' => $this->faker->randomFloat(2, 10, 100),
        ];
    }
    public function configure()
    {
        return $this->afterCreating(function (Book $book) {
            $categoryIds = Category::pluck('id')->toArray();
            $book->categories()->attach($this->faker->randomElements($categoryIds, $this->faker->numberBetween(1, 3)));
        });
    }
}
