<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Eliminar inscripciones antiguas que tienen crew_id diferente de 1
        // Solo conservar las que tienen crew_id = 1 (Todos los planteles)
        DB::table('amounts')
            ->where('receipt_type_id', 1)
            ->where('crew_id', '!=', 1)
            ->delete();

        // Opcional: También puedes eliminar otros tipos de costos si quieres estandarizarlos
        // Descomenta las siguientes líneas si quieres aplicar el mismo formato a todos los costos
        /*
        DB::table('amounts')
            ->where('receipt_type_id', '!=', 1)
            ->where('crew_id', '!=', 1)
            ->delete();
        */
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No hay forma de revertir esta migración ya que eliminamos datos
        // Si necesitas revertir, tendrás que regenerar los amounts desde el controlador
    }
};
