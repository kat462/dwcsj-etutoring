<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = ['name', 'code', 'education_level'];

    public function tutors()
    {
        return $this->belongsToMany(User::class, 'subject_user', 'subject_id', 'user_id');
    }

    /**
     * Bookings for this subject
     */
    public function bookings()
    {
        return $this->hasMany(\App\Models\Booking::class, 'subject_id');
    }
}
