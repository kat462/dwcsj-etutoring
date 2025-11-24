<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\TutorProfile;
use App\Models\Subject;
use App\Models\Availability;
use App\Models\Booking;
use App\Models\Feedback;
use Illuminate\Support\Facades\Hash;

class BookingSmokeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Booking smoke test logic removed. Only subject setup remains.');
    }
}
