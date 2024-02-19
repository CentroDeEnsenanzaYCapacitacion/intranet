<?php

namespace Database\Seeders;

use App\Models\ReceiptType;
use App\Models\RecipeType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReceiptTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ReceiptType::create([
            'name'=>'Inscripción'
        ]);
        ReceiptType::create([
            'name'=>'Colegiatura'
        ]);
        ReceiptType::create([
            'name'=>'Ingreso',
            'is_global'=>true
        ]);
    }
}
