<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->truncate();

        DB::table('categories')->insert([
            [
                "name" => "Book",
                "slug" => "book",
            ],
            [
                "name" => "Stationery",
                "slug" => "stationery",
            ],
            [
                "name" => "laptop",
                "slug" => "laptop",
            ],
            [
                "name" => "chair",
                "slug" => "chair",
            ]
        ]);
    }
}
