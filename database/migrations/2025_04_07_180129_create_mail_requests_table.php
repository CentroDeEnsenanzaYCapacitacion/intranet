<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mail_requests', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('staff_id')->constrained('staff')->onDelete('cascade');
            $table->boolean('is_created')->default(false);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mail_requests');
    }
};
