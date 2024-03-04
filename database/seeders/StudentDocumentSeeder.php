<?php

namespace Database\Seeders;

use App\Models\StudentDocument;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StudentDocument::create([
            'name'=>'Certificado de nacimiento'
        ]);
        StudentDocument::create([
            'name'=>'Certificado de nacimiento - Copia'
        ]);
        StudentDocument::create([
            'name'=>'CURP'
        ]);
        StudentDocument::create([
            'name'=>'INE'
        ]);
        StudentDocument::create([
            'name'=>'Certificado de secundaria'
        ]);
        StudentDocument::create([
            'name'=>'Certificado de secundaria - Copia'
        ]);
        StudentDocument::create([
            'name'=>'Fotografías'
        ]);
        StudentDocument::create([
            'name'=>'Certificado parcial'
        ]);
        StudentDocument::create([
            'name'=>'Certificado parcial - Copia'
        ]);
        StudentDocument::create([
            'name'=>'Certificado de bachillerato'
        ]);
        StudentDocument::create([
            'name'=>'Certificado de bachillerato - Copia'
        ]);
        StudentDocument::create([
            'name'=>'Ccomprobante de domicilio'
        ]);
        StudentDocument::create([
            'name'=>'Resolución de equivalencia'
        ]);
        StudentDocument::create([
            'name'=>'Revalidación'
        ]);
    }
}
