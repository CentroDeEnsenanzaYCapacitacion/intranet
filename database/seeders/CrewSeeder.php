<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Crew;

class CrewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Crew::create([
            'name' => 'Todos'
        ]);
        Crew::create([
            'name' => 'Chimalhuacan'
        ]);
        Crew::create([
            'name' => 'Ixtapaluca'
        ]);
        Crew::create([
            'name' => 'Texcoco'
        ]);
    }
}
