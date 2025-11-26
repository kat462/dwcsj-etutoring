<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Booking;

/**
 * Class Availability
 *
 * @property int $id
 * @property int $user_id
 * @property \Carbon\Carbon $date
 * @property string $start_time
 * @property string $end_time
 * @property bool $is_booked
 * @property \Illuminate\Support\Carbon|null $deleted_at
 */
class Availability extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'date', 'start_time', 'end_time', 'is_booked'
    ];

    protected $casts = [
        'date' => 'date',
        'is_booked' => 'boolean',
    ];

    public function tutor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function booking()
    {
        return $this->hasOne(Booking::class, 'availability_id');
    }

    public function subject()
    {
        return $this->belongsTo(\App\Models\Subject::class, 'subject_id');
    }
}

