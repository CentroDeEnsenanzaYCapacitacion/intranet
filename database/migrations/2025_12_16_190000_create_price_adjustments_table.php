<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('price_adjustments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->decimal('catalog_amount', 10, 2)->nullable();
            $table->decimal('entered_amount', 10, 2);
            $table->text('price_explanation');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('price_adjustments');
    }
};
