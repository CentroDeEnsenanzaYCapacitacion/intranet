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
        Schema::create('paybills', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->require;
            $table->string('receives');
            $table->string('concept');
            $table->string('amount');
            $table->unsignedBigInteger('crew_id')->require;
            $table->timestamps();
            $table->foreign('crew_id')->references('id')->on('crews');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paybills');
    }
};
