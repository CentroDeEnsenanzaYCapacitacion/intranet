<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('crew_id')->require;
            $table->string('name')->require;
            $table->string('surnames')->require;
            $table->string('genre')->require;
            $table->string('birthdate')->nullable();
            $table->string('address')->nullable();
            $table->string('colony')->nullable();
            $table->string('municipality')->nullable();
            $table->string('PC')->nullable();
            $table->string('phone')->nullable();
            $table->string('cel_phone')->nullable();
            $table->string('email')->require;
            $table->unsignedBigInteger('generation_id')->nullable();
            $table->unsignedBigInteger('modality_id')->nullable();
            $table->unsignedBigInteger('payment_periodicity_id')->nullable();
            $table->unsignedBigInteger('course_id')->require;
            $table->string('schedule')->nullable();
            $table->boolean('sabbatine')->default(false);
            $table->date('start')->nullable();
            $table->string('curp')->nullable();
            $table->timestamps();
            $table->foreign('crew_id')->references('id')->on('crews');
            $table->foreign('generation_id')->references('id')->on('generations');
            $table->foreign('modality_id')->references('id')->on('modalities');
            $table->foreign('payment_periodicity_id')->references('id')->on('payment_periodicities');
            $table->foreign('course_id')->references('id')->on('courses');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
