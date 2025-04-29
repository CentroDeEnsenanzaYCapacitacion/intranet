<?php

// database/migrations/xxxx_xx_xx_create_adjustment_definitions_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdjustmentDefinitionsTable extends Migration
{
    public function up()
    {
        Schema::create('adjustment_definitions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->enum('type', ['perception', 'deduction']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('adjustment_definitions');
    }
}
