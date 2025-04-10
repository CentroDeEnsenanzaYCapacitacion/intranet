<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            CrewSeeder::class,
            UserSeeder::class,
            MarketingSeeder::class,
            CourseSeeder::class,
            RequestTypeSeeder::class,
            ReceiptTypeSeeder::class,
            PaymentTypeSeeder::class,
            StudentDocumentSeeder::class,
            PaymentPeriodicitySeeder::class,
            ScheduleSeeder::class,
            ModalitySeeder::class,
            ReceiptAttributeSeeder::class,
            WebCarouselSeeder::class,
            WebMvvSeeder::class,
            SubjectSeeder::class
        ]);

        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
