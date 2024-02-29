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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('name')->require;
            $table->string('surnames')->require;
            $table->string('email')->require;
            $table->string('phone')->nullable();
            $table->string('cel_phone')->nullable();
            $table->unsignedBigInteger('course_id')->require;
            $table->unsignedBigInteger('marketing_id')->require;
            $table->unsignedBigInteger('crew_id')->require;
            $table->unsignedBigInteger('responsible_id')->require;
            $table->string('genre', 2)->require;
            $table->boolean('signed')->default(0);
            $table->timestamps();
            $table->foreign('crew_id')->references('id')->on('crews');
            $table->foreign('responsible_id')->references('id')->on('users');
            $table->foreign('course_id')->references('id')->on('courses');
            $table->foreign('marketing_id')->references('id')->on('marketings');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
