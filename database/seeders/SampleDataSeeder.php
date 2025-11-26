<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Subject;
use App\Models\Booking;
use App\Models\Feedback;
use Carbon\Carbon;

class SampleDataSeeder extends Seeder
{
    public function run()
    {
        // Create subjects
        $subjects = [
            'Mathematics', 'Science', 'English', 'Filipino', 'History', 'Computer Science'
        ];
        foreach ($subjects as $name) {
            Subject::firstOrCreate(['name' => $name]);
        }

        // Create users
        $admin = User::firstOrCreate([
            'email' => 'admin@example.com',
        ], [
            'student_id' => 'ADMIN1001',
            'name' => 'Admin User',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => 1,
        ]);
        $tutor = User::firstOrCreate([
            'email' => 'tutor@example.com',
        ], [
            'student_id' => 'TUTOR1001',
            'name' => 'Tutor One',
            'password' => Hash::make('password'),
            'role' => 'tutor',
            'is_active' => 1,
        ]);
        $tutee = User::firstOrCreate([
            'email' => 'tutee@example.com',
        ], [
            'student_id' => 'TUTEE1001',
            'name' => 'Tutee One',
            'password' => Hash::make('password'),
            'role' => 'tutee',
            'is_active' => 1,
        ]);

        // Assign subjects to tutor
        $tutor->subjects()->sync(Subject::pluck('id')->take(2));

        // Create a booking
        $booking = Booking::firstOrCreate([
            'tutor_id' => $tutor->id,
            'tutee_id' => $tutee->id,
            'subject_id' => Subject::first()->id,
        ], [
            'scheduled_at' => Carbon::now()->addDays(2),
            'status' => 'accepted',
        ]);

        // Create feedback
        Feedback::firstOrCreate([
            'booking_id' => $booking->id,
            'tutor_id' => $tutor->id,
            'tutee_id' => $tutee->id,
        ], [
            'rating' => 5,
            'comment' => 'Great session!',
        ]);
    }
}
