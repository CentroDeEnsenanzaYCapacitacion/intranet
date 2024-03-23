<?php

namespace Database\Seeders;

use App\Models\PaymentPeriodicity;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentPeriodicitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PaymentPeriodicity::create([
            'name'=>'Mensual'
        ]);
        PaymentPeriodicity::create([
            'name'=>'Semanal'
        ]);
    }
}
