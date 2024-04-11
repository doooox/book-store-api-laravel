<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = ["Action", "Advanture", "Biography", "Drama", "Education", "Epic Fantacy", "Fantacy", "Classic", "Comedy", "Romance", "Mithology", "Music", "Si-Fy", "Sport", "Thriller"];

        foreach ($categories as $category) {
            DB::table('categories')->insert([
                "category" => $category
            ]);
        }
    }
}
