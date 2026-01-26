<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $administrativeDepartments = [
            'Dirección',
            'Secretaría',
            'Intendencia',
        ];

        foreach ($administrativeDepartments as $name) {
            $exists = DB::table('departments')->where('name', $name)->exists();

            if (!$exists) {
                DB::table('departments')->insert([
                    'name' => $name,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        DB::table('departments')
            ->whereIn('name', ['Dirección', 'Secretaría', 'Intendencia'])
            ->delete();
    }
};
