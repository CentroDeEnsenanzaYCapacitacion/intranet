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
        Schema::create('sysrequests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('request_type_id')->require;
            $table->string('description')->require;
            $table->boolean('approved')->default(0);
            $table->unsignedBigInteger('user_id')->require;
            $table->unsignedBigInteger('report_id')->require;
            $table->timestamps();
            $table->foreign('request_type_id')->references('id')->on('request_types');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('report_id')->references('id')->on('reports');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sysrequests');
    }
};
