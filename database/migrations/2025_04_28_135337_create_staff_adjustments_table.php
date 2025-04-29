<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStaffAdjustmentsTable extends Migration
{
    public function up()
    {
        Schema::create('staff_adjustments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->constrained('staff')->onDelete('cascade');
            $table->foreignId('adjustment_definition_id')->constrained('adjustment_definitions')->onDelete('cascade');
            $table->foreignId('crew_id')->constrained('crews');
            $table->decimal('amount', 10, 2);
            $table->integer('year');
            $table->integer('month');
            $table->string('period');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('staff_adjustments');
    }
}
