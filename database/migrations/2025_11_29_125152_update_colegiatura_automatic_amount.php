<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Desactivar automatic_amount para Colegiatura (id=2)
        DB::table('receipt_types')
            ->where('id', 2)
            ->update(['automatic_amount' => false]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reactivar automatic_amount para Colegiatura
        DB::table('receipt_types')
            ->where('id', 2)
            ->update(['automatic_amount' => true]);
    }
};
