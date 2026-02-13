<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::getConnection()->getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys=off');
            DB::statement('
                CREATE TABLE sys_requests_temp AS SELECT * FROM sys_requests
            ');
            Schema::drop('sys_requests');
            Schema::create('sys_requests', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('request_type_id');
                $table->string('description');
                $table->boolean('approved')->nullable();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('report_id')->nullable();
                $table->unsignedBigInteger('student_id')->nullable();
                $table->unsignedBigInteger('receipt_id')->nullable();
                $table->unsignedBigInteger('paybill_id')->nullable();
                $table->timestamps();
            });
            DB::statement('
                INSERT INTO sys_requests (id, request_type_id, description, approved, user_id, report_id, student_id, created_at, updated_at)
                SELECT id, request_type_id, description, approved, user_id, report_id, student_id, created_at, updated_at
                FROM sys_requests_temp
            ');
            DB::statement('DROP TABLE sys_requests_temp');
            DB::statement('PRAGMA foreign_keys=on');
        } else {
            Schema::table('sys_requests', function (Blueprint $table) {
                $table->unsignedBigInteger('report_id')->nullable()->change();
                $table->unsignedBigInteger('receipt_id')->nullable()->after('student_id');
                $table->unsignedBigInteger('paybill_id')->nullable()->after('receipt_id');
                $table->foreign('receipt_id')->references('id')->on('receipts');
                $table->foreign('paybill_id')->references('id')->on('paybills');
            });
        }

        DB::table('request_types')->insert([
            'name' => 'Cambio de importe',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    public function down(): void
    {
        Schema::table('sys_requests', function (Blueprint $table) {
            if (Schema::getConnection()->getDriverName() !== 'sqlite') {
                $table->dropForeign(['receipt_id']);
                $table->dropForeign(['paybill_id']);
            }
            $table->dropColumn(['receipt_id', 'paybill_id']);
        });

        DB::table('request_types')->where('name', 'Cambio de importe')->delete();
    }
};
