<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        User::updateOrCreate(
            ['student_id' => 'ADMIN001'],
            [
                'name' => 'Site Admin',
                'email' => 'admin@example.test',
                'role' => 'admin',
                'education_level' => 'college',
                'password' => 'AdminPass123!',
                'is_active' => 1,
            ]
        );
    }
}
