<?php

namespace Database\Seeders;

use App\Models\Grade;
use App\Models\Student;
use App\Models\Exam;
use Illuminate\Database\Seeder;

class GradeSeeder extends Seeder
{
    public function run(): void
    {
        $students = Student::all();
        $exams = Exam::all();

        foreach ($students as $student) {
            // Get exams for the student's class
            $studentExams = $exams->where('academic_class_id', $student->academic_class_id);
            
            foreach ($studentExams as $exam) {
                $marks = rand(35, 100);
                $grade = $this->calculateGrade($marks);
                
                Grade::create([
                    'student_id' => $student->id,
                    'exam_id' => $exam->id,
                    'marks' => $marks,
                    'grade' => $grade,
                    'remarks' => $this->getRemarks($marks),
                    'graded_by' => 1, // Admin user
                    'graded_at' => now(),
                ]);
            }
        }
    }

    private function calculateGrade($marks): string
    {
        if ($marks >= 90) return 'A+';
        if ($marks >= 85) return 'A';
        if ($marks >= 80) return 'A-';
        if ($marks >= 75) return 'B+';
        if ($marks >= 70) return 'B';
        if ($marks >= 65) return 'B-';
        if ($marks >= 60) return 'C+';
        if ($marks >= 55) return 'C';
        if ($marks >= 50) return 'C-';
        if ($marks >= 45) return 'D+';
        if ($marks >= 40) return 'D';
        return 'F';
    }

    private function getRemarks($marks): string
    {
        if ($marks >= 90) return 'Outstanding performance! Keep up the excellent work!';
        if ($marks >= 80) return 'Very good performance. Keep up the great work!';
        if ($marks >= 70) return 'Good performance. Room for improvement.';
        if ($marks >= 60) return 'Satisfactory performance. Need more effort.';
        if ($marks >= 50) return 'Average performance. Significant improvement needed.';
        if ($marks >= 40) return 'Below average. Must work harder.';
        return 'Poor performance. Immediate attention required.';
    }
}
