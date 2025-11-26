<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class AllowedIdAuditLog extends Model
{
    protected $fillable = [
        'allowed_student_id', 'user_id', 'used_at', 'action', 'note'
    ];
    public function allowedStudentId() { return $this->belongsTo(AllowedStudentId::class); }
    public function user() { return $this->belongsTo(User::class); }
}
