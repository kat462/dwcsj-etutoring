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
        $this->command->info('Starting booking smoke test...');

        // Create or get a subject
        $subject = Subject::first() ?? Subject::create(['name' => 'Sample Subject', 'education_level' => 'College']);

        // Tutor
        $tutor = User::firstWhere('student_id', 'TUTOR_SMOKE');
        if (! $tutor) {
            $tutor = User::create([
                'student_id' => 'TUTOR_SMOKE',
                'name' => 'Tutor Smoke',
                'email' => 'tutor-smoke@example.test',
                'password' => Hash::make('password'),
                'role' => 'tutor',
                'education_level' => 'College',
                'is_active' => 1,
            ]);
        }

        // Attach subject to tutor if pivot exists
        if (! $tutor->subjects()->where('subject_id', $subject->id)->exists()) {
            $tutor->subjects()->attach($subject->id);
        }

        // Tutee
        $tutee = User::firstWhere('student_id', 'STUDENT_SMOKE');
        if (! $tutee) {
            $tutee = User::create([
                'student_id' => 'STUDENT_SMOKE',
                'name' => 'Student Smoke',
                'email' => 'student-smoke@example.test',
                'password' => Hash::make('password'),
                'role' => 'tutee',
                'education_level' => 'College',
                'is_active' => 1,
            ]);
        }

        // Create an availability for tomorrow
        $date = now()->addDay()->toDateString();
        $availability = Availability::create([
            'user_id' => $tutor->id,
            'date' => $date,
            'start_time' => '10:00:00',
            'end_time' => '11:00:00',
            'is_booked' => false,
        ]);

        $this->command->info("Created availability id={$availability->id} for tutor {$tutor->student_id} on {$date}");

        // Student requests booking using the availability
        $booking = Booking::create([
            'tutor_id' => $tutor->id,
            'tutee_id' => $tutee->id,
            'availability_id' => $availability->id,
            'scheduled_at' => $date . ' 10:00:00',
            'subject_id' => $subject->id,
            'status' => 'pending',
            'notes' => 'Smoke test booking',
        ]);

        $this->command->info("Created booking id={$booking->id} status={$booking->status}");

        // Tutor accepts booking
        $booking->status = 'accepted';
        $booking->save();
        $this->command->info("Booking id={$booking->id} status updated to accepted");

        // Tutor completes booking
        $booking->status = 'completed';
        $booking->save();
        $this->command->info("Booking id={$booking->id} status updated to completed");

        // Student leaves feedback
        $fb = Feedback::create([
            'booking_id' => $booking->id,
            'tutor_id' => $tutor->id,
            'tutee_id' => $tutee->id,
            'rating' => 5,
            'comment' => 'Great session (smoke test)',
            'status' => 'approved',
        ]);

        $this->command->info("Feedback id={$fb->id} created for booking id={$booking->id}");
        $this->command->info('Booking smoke test completed successfully.');
    }
}
