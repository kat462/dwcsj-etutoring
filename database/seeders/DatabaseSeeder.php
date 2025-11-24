<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Subject;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create/Update admin (id: ADMIN001 / password: password123)
        User::updateOrCreate(
            ['student_id' => 'ADMIN001'],
            [
                'name' => 'System Administrator',
                'email' => 'admin@dwcsj.edu.ph',
                'role' => 'admin',
                'password' => Hash::make('password123'),
            ]
        );

        // Sample Tutor account (id: TUTOR001 / password: password123)
        User::updateOrCreate(
            ['student_id' => 'TUTOR001'],
            [
                'name' => 'Sample Tutor',
                'email' => 'tutor@dwcsj.edu.ph',
                'role' => 'tutor',
                'password' => Hash::make('password123'),
            ]
        );

        // Sample Student account (id: STUDENT001 / password: password123)
        User::updateOrCreate(
            ['student_id' => 'STUDENT001'],
            [
                'name' => 'Sample Student',
                'email' => 'student@dwcsj.edu.ph',
                'role' => 'tutee',
                'password' => Hash::make('password123'),
            ]
        );

        // Create default subjects
        $subjects = ['Mathematics', 'Science', 'English', 'Computer Studies', 'Accounting'];
        foreach ($subjects as $subject) {
            Subject::create(['name' => $subject]);
        }

        $this->command->info('✅ Database seeded successfully!');
        $this->command->info('🔐 Test Accounts:');
        $this->command->info('  - Admin    → ID: ADMIN001,   Password: password123');
        $this->command->info('  - Tutor    → ID: TUTOR001,   Password: password123');
        $this->command->info('  - Student  → ID: STUDENT001, Password: password123');
    }
}
