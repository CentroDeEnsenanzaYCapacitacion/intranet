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
        Schema::table('sys_requests', function (Blueprint $table) {
            if (Schema::getConnection()->getDriverName() !== 'sqlite') {
                $table->unsignedBigInteger('report_id')->nullable()->change();
            }

            $table->unsignedBigInteger('student_id')->nullable()->after('report_id');

            if (Schema::getConnection()->getDriverName() !== 'sqlite') {
                $table->foreign('student_id')->references('id')->on('students');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sys_requests', function (Blueprint $table) {
            if (Schema::getConnection()->getDriverName() !== 'sqlite') {
                $table->dropForeign(['student_id']);
            }

            $table->dropColumn('student_id');
        });
    }
};
