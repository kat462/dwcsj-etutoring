<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}

