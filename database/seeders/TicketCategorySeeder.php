<?php

namespace Database\Seeders;

use App\Models\TicketCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TicketCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Problema de acceso / Login',
            'Error al cargar página o datos',
            'Información incorrecta o desactualizada',
            'No puedo realizar una acción',
            'Problema con reportes o documentos',
            'Problema con pagos o recibos',
            'Problema con estudiantes o cursos',
            'Problema con usuarios o permisos',
            'Solicitud de nueva funcionalidad',
            'Sugerencia de mejora',
            'Consulta o duda',
            'Otro',
        ];

        foreach ($categories as $name) {
            TicketCategory::firstOrCreate(['name' => $name]);
        }
    }
}
