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
            'name' => 'Todos',
            'adress' => '',
            'mail' => '',
            'phone' => ''
        ]);
        Crew::create([
            'name' => 'Chimalhuacan',
            'adress' => 'AV. NEZAHUALCOYOTL #5 CABECERA MUNICIPAL',
            'mail' => 'chimalhuacan@capacitacioncec.edu.mx',
            'phone' => '51138136'
        ]);
        Crew::create([
            'name' => 'Ixtapaluca',
            'adress' => 'AV CUAUHTEMOC #28 IXTAPALUCA',
            'mail' => 'ixtapaluca@capacitacioncec.edu.mx',
            'phone' => '59723763'
        ]);
        Crew::create([
            'name' => 'Texcoco',
            'adress' => 'AV. JUAREZ SUR #307 ESQ. ALDAMA CENTRO TEXCOCO',
            'mail' => 'texcoco@capacitacioncec.edu.mx',
            'phone' => '(01 595) 9555607'
        ]);
    }
}
