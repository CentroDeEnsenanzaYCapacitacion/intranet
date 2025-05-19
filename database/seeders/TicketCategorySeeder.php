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
            'Interfaz / UX',
            'Lógica / Funcionalidad',
            'Rendimiento',
            'Base de datos / Datos',
            'Integraciones / API',
            'Seguridad',
            'Permisos y roles',
            'Notificaciones / Correos',
            'Infraestructura / DevOps',
            'Compatibilidad / Dispositivos',
            'Internacionalización / Contenido',
            'Documentación',
            'Otro',
        ];

        foreach ($categories as $name) {
            TicketCategory::firstOrCreate(['name' => $name]);
        }
    }
}
