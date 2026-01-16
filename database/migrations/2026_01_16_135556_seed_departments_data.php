<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::table('departments')->count() > 0) {
            return;
        }

        $departments = [
            'Bachillerato' => [1, 2, 6, 8, 10],
            'Secundaria' => [3, 7, 9, 11],
            'Licenciatura' => [15, 16, 17, 18, 19, 20, 21],
            'Informática' => [4, 14],
            'Inglés' => [5],
            'Gastronomía' => [12],
            'Kids' => [13],
        ];

        foreach ($departments as $name => $courseIds) {
            $departmentId = DB::table('departments')->insertGetId([
                'name' => $name,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('courses')
                ->whereIn('id', $courseIds)
                ->update(['department_id' => $departmentId]);
        }
    }

    public function down(): void
    {
        DB::table('courses')->update(['department_id' => null]);
        DB::table('departments')->truncate();
    }
};
