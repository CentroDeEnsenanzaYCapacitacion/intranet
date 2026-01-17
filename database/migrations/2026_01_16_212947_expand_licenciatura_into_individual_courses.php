<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $licenciaturaDept = DB::table('departments')->where('name', 'Licenciatura')->first();

        if (!$licenciaturaDept) {
            return;
        }

        $courses = DB::table('courses')->where('department_id', $licenciaturaDept->id)->get();

        foreach ($courses as $course) {
            $newDeptId = DB::table('departments')->insertGetId([
                'name' => $course->name,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('courses')
                ->where('id', $course->id)
                ->update(['department_id' => $newDeptId]);
        }

        DB::table('staff_department_costs')
            ->where('department_id', $licenciaturaDept->id)
            ->delete();

        DB::table('departments')->where('id', $licenciaturaDept->id)->delete();
    }

    public function down(): void
    {
        $licenciaturaId = DB::table('departments')->insertGetId([
            'name' => 'Licenciatura',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $courseNames = DB::table('courses')
            ->join('departments', 'courses.department_id', '=', 'departments.id')
            ->whereIn('courses.id', [15, 16, 17, 18, 19, 20, 21])
            ->pluck('departments.id')
            ->unique();

        DB::table('courses')
            ->whereIn('id', [15, 16, 17, 18, 19, 20, 21])
            ->update(['department_id' => $licenciaturaId]);

        DB::table('departments')->whereIn('id', $courseNames)->delete();
    }
};
