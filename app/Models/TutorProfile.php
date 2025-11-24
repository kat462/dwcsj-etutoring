<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TutorProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'bio',
        'education_level',
        'facebook',
        'instagram',
        'other_link',
        'profile_image',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
