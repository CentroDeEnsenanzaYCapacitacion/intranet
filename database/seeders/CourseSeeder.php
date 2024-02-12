<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Course;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Course::create([
            'name' => 'Bachillerato general',
            'crew_id'=>1
        ]);
        Course::create([
            'name' => 'Bachillerato en un exámen',
            'crew_id'=>1
        ]);
        Course::create([
            'name' => 'Secundaria abierta',
            'crew_id'=>1
        ]);
        Course::create([
            'name' => 'Informática',
            'crew_id'=>1
        ]);
        Course::create([
            'name' => 'Inglés',
            'crew_id'=>1
        ]);
        Course::create([
            'name' => 'Bachillerato / Informática',
            'crew_id'=>1
        ]);
        Course::create([
            'name' => 'Secundaria / Informática',
            'crew_id'=>1
        ]);
        Course::create([
            'name' => 'Bachillerato / Inglés',
            'crew_id'=>1
        ]);
        Course::create([
            'name' => 'Secundaria / Inglés',
            'crew_id'=>1
        ]);
        Course::create([
            'name' => 'Bach. / Inf. / Ing.',
            'crew_id'=>1
        ]);
        Course::create([
            'name' => 'Sec. / Inf. / Ing.',
            'crew_id'=>1
        ]);
        Course::create([
            'name' => 'Gastronomia',
            'crew_id'=>2
        ]);
        Course::create([
            'name' => 'Kids',
            'crew_id'=>2
        ]);
        Course::create([
            'name' => 'Informática / Inglés',
            'crew_id'=>1
        ]);
        Course::create([
            'name' => 'Lic. en derecho',
            'crew_id'=>1
        ]);
        Course::create([
            'name' => 'Lic. en admon. de empresas',
            'crew_id'=>1
        ]);
        Course::create([
            'name' => 'Lic. en comercio ext. y aduanas',
            'crew_id'=>1
        ]);
        Course::create([
            'name' => 'Lic. en derecho por competencia',
            'crew_id'=>1
        ]);
        Course::create([
            'name' => 'Lic. en psicología',
            'crew_id'=>1
        ]);
        Course::create([
            'name' => 'Lic. en pedagogía',
            'crew_id'=>1
        ]);
        Course::create([
            'name' => 'Lic. en contaduría',
            'crew_id'=>1
        ]);
        Course::create([
            'name' => 'Lic. por competencia',
            'crew_id'=>1
        ]);
    }
}
