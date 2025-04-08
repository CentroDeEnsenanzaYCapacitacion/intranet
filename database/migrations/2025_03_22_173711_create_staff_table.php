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
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->string('name')->require;
            $table->string('surnames')->nullable();
            $table->string('Address')->nullable();
            $table->string('colony')->nullable();
            $table->string('municipalty')->nullable();
            $table->string('phone')->nullable();
            $table->string('cel')->nullable();
            $table->string('rfc')->nullable();
            $table->string('department')->nullable();
            $table->string('personal_mail')->nullable();
            $table->string('cec_mail')->nullable();
            $table->string('position')->nullable();
            $table->boolean('isActive')->default(true);
            $table->boolean('requiresMail')->default(true);
            $table->boolean('isRoster')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};
