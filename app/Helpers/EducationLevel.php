<?php

namespace App\Helpers;

class EducationLevel
{
    public const LEVELS = [
        'kindergarten' => 'Kindergarten',
        'elementary' => 'Elementary',
        'junior_high' => 'Junior High School',
        'senior_high' => 'Senior High School',
        'college' => 'College',
        'other' => 'Other',
    ];

    public static function all()
    {
        return array_keys(self::LEVELS);
    }

    public static function options()
    {
        return self::LEVELS;
    }

    public static function label($key)
    {
        return self::LEVELS[$key] ?? $key;
    }
}
