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

        // Seed allowed student IDs (2 only)
        $this->call([
            AllowedStudentIdsSeeder::class,
        ]);

        // Create default subjects
        $subjects = [
            'Mathematics',
            'Science',
            'English',
            'Computer Studies',
            'Accounting',
        ];

        foreach ($subjects as $subject) {
            Subject::create(['name' => $subject]);
        }

        $this->command->info('✅ Database seeded successfully!');
        $this->command->info('  - Admin    → ID: ADMIN001,   Password: adpass123');
        $this->command->info('  - Allowed Student IDs: DWC001, DWC002');
    }
}
