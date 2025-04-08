<?php

namespace Database\Seeders;

use App\Models\RequestType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RequestTypeSeeder extends Seeder
{

    public function run(): void
    {
        RequestType::create([
            'name'=>'Descuento de inscripción'
        ]);
        RequestType::create([
            'name'=>'Creación correo institucional'
        ]);
    }
}
