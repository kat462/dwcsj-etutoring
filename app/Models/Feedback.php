<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Feedback extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $table = 'feedback';
    
    protected $fillable = [
        'booking_id',
        'tutor_id',
        'tutee_id',
        'rating',
        'comment',
        'status',
        'decline_reason'
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function tutor()
    {
        return $this->belongsTo(User::class, 'tutor_id');
    }

    public function tutee()
    {
        return $this->belongsTo(User::class, 'tutee_id');
    }
}
