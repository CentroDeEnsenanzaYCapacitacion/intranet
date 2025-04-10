<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $subjects = [
            'MATEMÁTICAS I', 'QUÍMICA I', 'GEOGRAFÍA', 'INTRODUCCIÓN A LAS CIENCIAS SOCIALES',
            'INFORMÁTICA I', 'TALLER DE LECTURA Y REDACCIÓN I', 'INGLÉS I',
            'MATEMÁTICAS I', 'QUÍMICA II', 'BIOLOGÍA I', 'HISTORIA DE MÉXICO I',
            'INFORMÁTICA II', 'TALLER DE LECTURA Y REDACCIÓN II', 'INGLÉS II',
            'MATEMÁTICAS III', 'FÍSICA I', 'BIOLOGÍA II', 'HISTORIA DE MÉXICO II',
            'MÉTODOS DE LA INVESTIGACIÓN I', 'LITERATURA I', 'INGLÉS III',
            'MATEMÁTICAS IV', 'FÍSICA II', 'ECOLOGÍA Y MEDIO AMBIENTE', 'COMUNICACIÓN APLICADA',
            'MÉTODOS DE LA INVESTIGACIÓN II', 'LITERATURA II', 'INGLÉS IV',
            'INDIVIDUO Y SOCIEDAD', 'PSICOLOGÍA DEL DESARROLLO', 'DERECHO I',
            'INTRODUCCIÓN AL TRABAJO SOCIAL', 'MATEMÁTICAS V', 'FUNDAMENTOS DE LA MERCADOTECNIA',
            'FILOSOFÍA', 'DERECHO LABORAL/ MERCANTIL', 'INTRODUCCIÓN A LA PSICOLOGÍA APLICADA',
            'ADMINISTRACIÓN', 'MATEMÁTICAS VI', 'MERCADOTECNIA APLICADA',
        ];

        foreach ($subjects as $name) {
            Subject::create([
                'name' => $name,
                'is_active' => true,
                'course_id' => 1
            ]);
        }
    }
}
