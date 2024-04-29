<?php

namespace Database\Seeders;

use App\Models\ReceiptType;
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
            'name'=>'Inscripción',
            'automatic_amount'=>true
        ]);
        ReceiptType::create([
            'name'=>'Colegiatura',
            'automatic_amount'=>true
        ]);
        ReceiptType::create([
            'name'=>'Reinscripción'
        ]);
        ReceiptType::create([
            'name'=>'Constancia'
        ]);
        ReceiptType::create([
            'name'=>'Extraordinario primera vuelta'
        ]);
        ReceiptType::create([
            'name'=>'Extraordinario segunda vuelta'
        ]);
        ReceiptType::create([
            'name'=>'Credencial'
        ]);
        ReceiptType::create([
            'name'=>'Legalización'
        ]);
        ReceiptType::create([
            'name'=>'Renuncia'
        ]);
        ReceiptType::create([
            'name'=>'Reiscripción Licenciatura'
        ]);
        ReceiptType::create([
            'name'=>'Ingreso'
        ]);
    }
}
