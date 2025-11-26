<?php
namespace App\Services;

use App\Models\AllowedIdAuditLog;
use App\Models\AllowedStudentId;
use App\Models\User;

class AllowedIdAuditService
{
    public static function logUsage(AllowedStudentId $allowed, User $user, string $action = 'used', string $note = null)
    {
        AllowedIdAuditLog::create([
            'allowed_student_id' => $allowed->id,
            'user_id' => $user->id,
            'used_at' => now(),
            'action' => $action,
            'note' => $note,
        ]);
    }
}
