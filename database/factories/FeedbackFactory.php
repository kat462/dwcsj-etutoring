<?php
namespace Database\Factories;

use App\Models\Feedback;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class FeedbackFactory extends Factory
{
    protected $model = Feedback::class;

    public function definition(): array
    {
        $booking = Booking::inRandomOrder()->first();
        $tutee = $booking ? $booking->tutee_id : User::factory();
        $tutor = $booking ? $booking->tutor_id : User::factory();
        return [
            'booking_id' => $booking ? $booking->id : Booking::factory(),
            'tutee_id' => $tutee,
            'tutor_id' => $tutor,
            'rating' => $this->faker->numberBetween(3, 5),
            'comment' => $this->faker->sentence(),
        ];
    }
}
