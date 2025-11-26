<?php
namespace Database\Factories;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class BookingFactory extends Factory
{
    protected $model = Booking::class;

    public function definition(): array
    {
        $tutor = User::where('role', 'tutor')->inRandomOrder()->first();
        $tutee = User::where('role', 'tutee')->inRandomOrder()->first();
        return [
            'tutor_id' => $tutor ? $tutor->id : User::factory(),
            'tutee_id' => $tutee ? $tutee->id : User::factory(),
            'subject_id' => 1, // You may randomize or relate to Subject
            'status' => $this->faker->randomElement(['pending', 'accepted', 'completed', 'cancelled']),
            'scheduled_at' => Carbon::now()->addDays(rand(1, 30)),
            'duration' => $this->faker->randomElement([30, 60, 90]),
        ];
    }
}
