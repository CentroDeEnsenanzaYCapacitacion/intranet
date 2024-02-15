<?php

namespace Database\Seeders;

use App\Models\Marketing;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MarketingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Marketing::create([
            'name' => 'Volante'
        ]);
        Marketing::create([
            'name' => 'Gallardete'
        ]);
        Marketing::create([
            'name' => 'Espectacular'
        ]);
        Marketing::create([
            'name' => 'Antigüo alumno'
        ]);
        Marketing::create([
            'name' => 'Página web'
        ]);
        Marketing::create([
            'name' => 'Pasaba por aquí...'
        ]);
    }
}
