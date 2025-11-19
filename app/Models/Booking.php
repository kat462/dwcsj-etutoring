<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Availability;
use App\Models\Feedback;
use App\Models\Subject;

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
}
 