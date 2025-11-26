<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\Models\Booking;

class NewBookingRequest extends Notification implements ShouldQueue
{
    use Queueable;
    public $booking;
    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Booking Request')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('You have received a new booking request from ' . $this->booking->tutee->name . '.')
            ->action('View Booking', url(route('tutor.bookings')))
            ->line('Subject: ' . ($this->booking->subject->name ?? '-'))
            ->line('Date: ' . ($this->booking->scheduled_at ? $this->booking->scheduled_at->format('M d, Y h:i A') : '-'))
            ->line('Notes: ' . ($this->booking->notes ?: '-'));
    }
    public function toDatabase($notifiable)
    {
        return [
            'booking_id' => $this->booking->id,
            'tutee_name' => $this->booking->tutee->name,
            'subject' => $this->booking->subject->name ?? '-',
            'scheduled_at' => $this->booking->scheduled_at,
            'notes' => $this->booking->notes,
            'message' => 'New booking request from ' . $this->booking->tutee->name,
        ];
    }
}
