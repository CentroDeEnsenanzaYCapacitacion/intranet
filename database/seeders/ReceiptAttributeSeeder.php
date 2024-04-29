<?php

namespace Database\Seeders;

use App\Models\ReceiptAttribute;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReceiptAttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ReceiptAttribute::create([
            'name'=>'Anticipo'
        ]);
        ReceiptAttribute::create([
            'name'=>'Complemento'
        ]);
        ReceiptAttribute::create([
            'name'=>'Reposición'
        ]);
        ReceiptAttribute::create([
            'name'=>'Recargo'
        ]);
    }
}
