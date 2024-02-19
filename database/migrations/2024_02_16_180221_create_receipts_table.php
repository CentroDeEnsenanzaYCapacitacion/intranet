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
        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('crew_id')->require;
            $table->unsignedBigInteger('responsible_id')->require;
            $table->unsignedBigInteger('receipt_type_id')->require;
            $table->unsignedBigInteger('payment_type_id')->require;
            $table->unsignedBigInteger('student_id')->nullable();
            $table->string('concept')->require;
            $table->string('amount')->require;
            $table->timestamps();
            $table->foreign('crew_id')->references('id')->on('crews');
            $table->foreign('responsible_id')->references('id')->on('users');
            $table->foreign('receipt_type_id')->references('id')->on('receipt_types');
            $table->foreign('payment_type_id')->references('id')->on('payment_types');
            $table->foreign('student_id')->references('id')->on('students');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receipts');
    }
};
