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
            $table->string('username', 50)->unique();
            $table->bigInteger('role_id');
            $table->boolean('is_active');
            $table->bigInteger('crew_id');
            $table->string('phone', 10)->nullable();
            $table->string('cel_phone', 10)->nullable();
            $table->string('genre', 1);
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
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
