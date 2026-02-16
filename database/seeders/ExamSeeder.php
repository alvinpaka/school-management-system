<?php

namespace Database\Seeders;

use App\Models\Exam;
use App\Models\AcademicClass;
use App\Models\Subject;
use Illuminate\Database\Seeder;

class ExamSeeder extends Seeder
{
    public function run(): void
    {
        $classes = AcademicClass::all();
        $examTypes = ['Mid Term', 'Final Term', 'Unit Test', 'Quiz'];
        
        // Use a sample of subjects from our new curriculum
        $oLevelSubjects = ['English', 'Mathematics', 'Biology', 'Chemistry', 'Physics', 'History & Political Education'];
        $aLevelSubjects = ['Biology', 'Chemistry', 'Physics', 'Mathematics'];
        $subjects = array_merge($oLevelSubjects, $aLevelSubjects);

        foreach ($classes as $class) {
            foreach ($examTypes as $type) {
                foreach ($subjects as $subject) {
                    $subjectModel = Subject::where('name', $subject)->first();
                    if ($subjectModel) {
                        Exam::firstOrCreate([
                            'name' => $type . ' - ' . $subject,
                            'subject_id' => $subjectModel->id,
                            'date' => now()->addDays(rand(1, 90)),
                            'start_time' => now()->setTime(9, 0),
                            'end_time' => now()->setTime(11, 0),
                            'max_marks' => 100,
                        ]);
                    }
                }
            }
        }
    }
}
