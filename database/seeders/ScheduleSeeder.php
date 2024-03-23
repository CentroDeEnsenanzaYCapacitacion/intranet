<?php

namespace Database\Seeders;

use App\Models\Schedule;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schedule::create([
            'name'=>'8 a 10'
        ]);
        Schedule::create([
            'name'=>'12 a 14'
        ]);
        Schedule::create([
            'name'=>'14 a 16'
        ]);
        Schedule::create([
            'name'=>'17 a 19'
        ]);
        Schedule::create([
            'name'=>'8 a 13'
        ]);
        Schedule::create([
            'name'=>'13 a 18'
        ]);
    }
}
