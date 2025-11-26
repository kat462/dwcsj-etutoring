<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AllowedStudentId extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'allowed_student_ids';
    protected $fillable = ['student_id', 'used'];

    /**
     * Check if a student id is allowed and not yet used. Optionally validate education level.
     */
    public static function isAllowed(string $studentId): bool
    {
        return static::where('student_id', $studentId)->where('used', false)->exists();
    }

    public function markUsed(): void
    {
        $this->used = true;
        $this->save();
    }
}
