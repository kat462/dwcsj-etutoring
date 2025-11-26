<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Subject;

class SubjectSeeder extends Seeder
{
    public function run()
    {
        $subjects = [
            // College
            ['name' => 'Calculus I', 'education_level' => 'College'],
            ['name' => 'English Composition', 'education_level' => 'College'],
            ['name' => 'Intro to Programming', 'education_level' => 'College'],
            ['name' => 'Biology I', 'education_level' => 'College'],
            ['name' => 'Accounting Fundamentals', 'education_level' => 'College'],
            // Senior High
            ['name' => 'General Mathematics', 'education_level' => 'Senior High'],
            ['name' => 'Physical Science', 'education_level' => 'Senior High'],
            ['name' => 'Oral Communication', 'education_level' => 'Senior High'],
            ['name' => 'Filipino', 'education_level' => 'Senior High'],
            // Junior High
            ['name' => 'Mathematics', 'education_level' => 'Junior High'],
            ['name' => 'Science', 'education_level' => 'Junior High'],
            ['name' => 'English', 'education_level' => 'Junior High'],
            ['name' => 'Filipino', 'education_level' => 'Junior High'],
            // Basic Ed
            ['name' => 'Mathematics', 'education_level' => 'Basic Ed'],
            ['name' => 'Science', 'education_level' => 'Basic Ed'],
            ['name' => 'English', 'education_level' => 'Basic Ed'],
            ['name' => 'Filipino', 'education_level' => 'Basic Ed'],
        ];
        foreach($subjects as $s) {
            Subject::updateOrCreate([
                'name' => $s['name'],
                'education_level' => $s['education_level']
            ], $s);
        }
    }
}
