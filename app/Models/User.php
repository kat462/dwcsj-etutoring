<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Hash;
use App\Models\Subject;
use App\Models\Availability;
use App\Models\Booking;
use App\Models\Feedback;
use App\Models\TutorProfile;

class User extends Authenticatable {
    use Notifiable, SoftDeletes;
    protected $fillable = ['student_id','name','email','password','role','course','education_level','facebook_url','instagram_url','linkedin_url','bio','is_active'];
    protected $hidden = ['password','remember_token'];
    public function setPasswordAttribute($value){ $this->attributes['password'] = Hash::needsRehash($value) ? Hash::make($value) : $value; }
    public function subjects(){ return $this->belongsToMany(Subject::class,'subject_user'); }
    public function availabilities(){ return $this->hasMany(Availability::class); }
    public function bookingsAsTutee(){ return $this->hasMany(Booking::class,'tutee_id'); }
    public function bookingsAsTutor(){ return $this->hasMany(Booking::class,'tutor_id'); }
    public function feedbacksGiven(){ return $this->hasMany(Feedback::class,'tutee_id'); }
    public function feedbacksReceived(){ return $this->hasMany(Feedback::class,'tutor_id'); }
    public function givenFeedback(){ return $this->feedbacksGiven(); }
    public function receivedFeedback(){ return $this->feedbacksReceived(); }
    public function profile(){ return $this->hasOne(TutorProfile::class); }

    // Role helpers
    public function isAdmin(): bool { return $this->role === 'admin'; }
    public function isTutor(): bool { return $this->role === 'tutor'; }
    public function isStudent(): bool { return $this->role === 'tutee' || $this->role === 'student'; }

    // Returns up to two-letter initials for display avatars
    public function initials(): string
    {
        $name = $this->name ?? '';
        $parts = preg_split('/\s+/', trim($name));
        $initials = '';

        foreach ($parts as $p) {
            if ($p === '') continue;
            $initials .= strtoupper(mb_substr($p, 0, 1));
            if (mb_strlen($initials) >= 2) break;
        }

        return $initials ?: strtoupper(mb_substr($name, 0, 1));
    }
}
