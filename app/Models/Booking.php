<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Availability;
use App\Models\Feedback;
use App\Models\Subject;
use Illuminate\Support\Facades\Schema;

/**
 * Class Booking
 *
 * @property int $id
 * @property int $tutor_id
 * @property int $tutee_id
 * @property int|null $availability_id
 * @property \Carbon\Carbon|null $scheduled_at
 * @property string $status
 */
class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'tutor_id', 'tutee_id', 'availability_id', 'scheduled_at', 'status', 'notes', 'subject_id'
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    public function tutor()
    {
        return $this->belongsTo(User::class, 'tutor_id');
    }

    public function tutee()
    {
        return $this->belongsTo(User::class, 'tutee_id');
    }

    public function availability()
    {
        return $this->belongsTo(Availability::class, 'availability_id');
    }

    public function feedback()
    {
        return $this->hasOne(Feedback::class, 'booking_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    // Backwards-compatible accessor used by views expecting session_date
    public function getSessionDateAttribute()
    {
        return $this->scheduled_at ? $this->scheduled_at : null;
    }

    // Return which column stores the tutee/student foreign key (tutee_id or student_id)
    public static function tuteeKey()
    {
        if (Schema::hasColumn('bookings', 'tutee_id')) {
            return 'tutee_id';
        }

        if (Schema::hasColumn('bookings', 'student_id')) {
            return 'student_id';
        }

        // default to tutee_id if schema unknown
        return 'tutee_id';
    }

    // Provide an accessor so $booking->tutee_id works even if DB column is student_id
    public function getTuteeIdAttribute()
    {
        if (array_key_exists('tutee_id', $this->attributes)) {
            return $this->attributes['tutee_id'];
        }

        if (array_key_exists('student_id', $this->attributes)) {
            return $this->attributes['student_id'];
        }

        return null;
    }
}
 