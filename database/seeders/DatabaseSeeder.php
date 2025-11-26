<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Subject;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        // Create/Update admin (id: ADMIN001 / password: adpass123)
        User::updateOrCreate(
            [
                'student_id' => 'ADMIN001',
            ],
            [
                'name' => 'System Administrator',
                'email' => 'admin@dwcsj.edu.ph',
                'role' => 'admin',
                'password' => Hash::make('adpass123'),
            ]
        );

        // Use SubjectSeeder for up-to-date subjects and education levels
        $this->call([
            SubjectSeeder::class,
            SampleDataSeeder::class,
        ]);

        // Demo tutors
        \App\Models\User::factory(5)->create(['role' => 'tutor']);
        // Demo tutees
        \App\Models\User::factory(10)->create(['role' => 'tutee']);

        // Demo bookings (randomly assigned)
        \App\Models\Booking::factory(15)->create();

        // Demo feedback (randomly assigned to bookings)
        \App\Models\Feedback::factory(10)->create();

        $this->command->info('✅ Database seeded successfully!');
        $this->command->info('  - Admin    → ID: ADMIN001,   Password: adpass123');
        $this->command->info('  - 5 tutors, 10 tutees, 15 bookings, 10 feedback records');
    }
}
