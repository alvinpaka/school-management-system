<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Student;
use App\Models\AcademicClass;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        $students = Student::all();
        $classes = AcademicClass::all();
        
        // Generate attendance for the last 30 days
        for ($day = 0; $day < 30; $day++) {
            $date = now()->subDays($day);
            
            // Skip weekends
            if ($date->isWeekend()) {
                continue;
            }
            
            foreach ($students as $student) {
                // Random attendance status
                $status = $this->getRandomStatus();
                
                Attendance::firstOrCreate([
                    'student_id' => $student->id,
                    'academic_class_id' => $student->academic_class_id,
                    'section_id' => $student->section_id,
                    'date' => $date->format('Y-m-d'),
                    'status' => $status,
                    'remarks' => $status === 'absent' ? 'Unexcused absence' : ($status === 'late' ? 'Arrived 10 minutes late' : null),
                ]);
            }
        }
    }

    private function getRandomStatus(): string
    {
        $statuses = ['present', 'present', 'present', 'present', 'present', 'present', 'present', 'late', 'absent'];
        return $statuses[array_rand($statuses)];
    }
}
