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
        Schema::create('documentations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->boolean('birth_certificate');
            $table->boolean('copy_birth_certificate');
            $table->boolean('secondary_certificate');
            $table->boolean('copy_secondary_certificate');
            $table->boolean('photos');
            $table->boolean('partial_certificate');
            $table->boolean('copy_partial_certificate');
            $table->boolean('bachelor_certificate');
            $table->boolean('copy_bachelor_certificate');
            $table->boolean('adress_proof');
            $table->boolean('equivalence_resolution');
            $table->boolean('revalidation');
            $table->timestamps();
            $table->foreign('student_id')->references('id')->on('students');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documentations');
    }
};
