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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('surnames', 100);
            $table->string('username', 50)->unique()->nullable();
            $table->unsignedBigInteger('role_id');
            $table->boolean('is_active')->default(1);
            $table->unsignedBigInteger('crew_id');
            $table->string('phone', 15)->nullable();
            $table->string('cel_phone', 15)->nullable();
            $table->string('genre', 2);
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->foreign('role_id')->references('id')->on('roles');
            $table->foreign('crew_id')->references('id')->on('crews');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
