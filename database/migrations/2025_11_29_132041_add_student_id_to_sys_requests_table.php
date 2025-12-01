<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('sys_requests', function (Blueprint $table) {
            // Hacer report_id nullable para solicitudes que no son de reportes
            $table->unsignedBigInteger('report_id')->nullable()->change();
            
            // Agregar student_id para solicitudes relacionadas con estudiantes
            $table->unsignedBigInteger('student_id')->nullable()->after('report_id');
            $table->foreign('student_id')->references('id')->on('students');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sys_requests', function (Blueprint $table) {
            $table->dropForeign(['student_id']);
            $table->dropColumn('student_id');
        });
    }
};
