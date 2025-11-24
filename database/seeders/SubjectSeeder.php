<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Subject;

class SubjectSeeder extends Seeder
{
    public function run()
    {
        $subjects = [
            // College subjects
            ['code' => 'MATH101', 'name' => 'Calculus I', 'education_level' => 'College'],
            ['code' => 'ENG101', 'name' => 'English Composition', 'education_level' => 'College'],
            ['code' => 'CS101', 'name' => 'Intro to Programming', 'education_level' => 'College'],
            ['code' => 'BIO101', 'name' => 'Biology I', 'education_level' => 'College'],
            ['code' => 'ACCT101', 'name' => 'Accounting Fundamentals', 'education_level' => 'College'],
            
            // Basic Ed subjects
            ['code' => 'MATH-BE', 'name' => 'Mathematics', 'education_level' => 'Basic Ed'],
            ['code' => 'SCI-BE', 'name' => 'Science', 'education_level' => 'Basic Ed'],
            ['code' => 'ENG-BE', 'name' => 'English', 'education_level' => 'Basic Ed'],
            ['code' => 'FIL-BE', 'name' => 'Filipino', 'education_level' => 'Basic Ed'],
        ];
        
        foreach($subjects as $s) {
            Subject::create($s);
        }
    }
}
