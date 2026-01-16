<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
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
            $department = Department::create(['name' => $name]);
            Course::whereIn('id', $courseIds)->update(['department_id' => $department->id]);
        }
    }
}
