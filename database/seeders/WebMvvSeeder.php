<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WebMvvSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('web_mvvs')->insert([
            [
                'name' => "Nosotros",
                'description' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => "Misión",
                'description' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => "Visión",
                'description' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => "Valores",
                'description' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ]);
    }
}
