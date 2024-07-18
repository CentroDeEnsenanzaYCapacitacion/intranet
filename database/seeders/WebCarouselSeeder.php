<?php

namespace Database\Seeders;

use App\Models\WebCarousel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class WebCarouselSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('web_carousels')->insert([
            [
                'title' => null,
                'description' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => null,
                'description' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => null,
                'description' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => null,
                'description' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
