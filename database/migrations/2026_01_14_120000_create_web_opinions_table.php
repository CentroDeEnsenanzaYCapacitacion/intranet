<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('web_opinions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('course')->nullable();
            $table->decimal('rating', 2, 1)->nullable();
            $table->text('description')->nullable();
            $table->string('image_extension', 5)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('web_opinions');
    }
};
