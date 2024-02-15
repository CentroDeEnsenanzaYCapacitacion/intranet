<?php

namespace Database\Seeders;

use App\Models\RecipeType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RecipeTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        RecipeType::create([
            'name'=>'Inscripción'
        ]);
        RecipeType::create([
            'name'=>'Colegiatura'
        ]);
        RecipeType::create([
            'name'=>'Ingreso',
            'is_global'=>true
        ]);
    }
}
