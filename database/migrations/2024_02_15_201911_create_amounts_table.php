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
        Schema::create('amounts', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->decimal('amount', 8, 2)->default("0.00");
            $table->unsignedBigInteger('crew_id')->require;
            $table->unsignedBigInteger('course_id')->nullable();
            $table->unsignedBigInteger('receipt_type_id')->require;
            $table->timestamps();
            $table->foreign('crew_id')->references('id')->on('crews');
            $table->foreign('course_id')->references('id')->on('courses');
            $table->foreign('receipt_type_id')->references('id')->on('receipt_types');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('amounts');
    }
};
