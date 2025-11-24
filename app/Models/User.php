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
use Illuminate\Support\Facades\Schema;

/**
 * Class User
 *
 * @property int $id
 * @property string|null $student_id
 * @property string $name
 * @property string $email
 * @property string $role
 */
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
    public function profile(){ return $this->hasOne(TutorProfile::class); }

    // Schema-aware profile accessor: some DBs use `user_id` or `tutor_id`.
    // Use an accessor to avoid Eloquent building a relation query that
    // references a missing column (which causes SQL errors in production).
    public function getProfileAttribute()
    {
        if (!Schema::hasTable('tutor_profiles')) {
            return null;
        }

        if (Schema::hasColumn('tutor_profiles', 'user_id')) {
            return TutorProfile::where('user_id', $this->id)->first();
        }

        if (Schema::hasColumn('tutor_profiles', 'tutor_id')) {
            return TutorProfile::where('tutor_id', $this->id)->first();
        }

        return null;
    }

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
