<?php

namespace App\Services;

use App\Models\AcademicClass;
use App\Models\Exam;
use App\Models\Grade;
use App\Models\Student;
use Illuminate\Support\Collection;

class GradeService extends BaseService
{
    protected int $cacheTTL = 300;

    public function getGradesForExam(Exam $exam, ?int $classId = null, ?int $sectionId = null): Collection
    {
        $cacheKey = $this->getCacheKey('grades:exam', [$exam->id, $classId, $sectionId]);

        return $this->remember($cacheKey, function () use ($exam, $classId, $sectionId) {
            $students = Student::query()
                ->select(['id', 'user_id', 'academic_class_id', 'section_id'])
                ->with(['user:id,name']);

            if ($classId) {
                $students->where('academic_class_id', $classId);
            }
            if ($sectionId) {
                $students->where('section_id', $sectionId);
            }

            $students = $students->get();

            $existingGrades = Grade::query()
                ->select(['id', 'student_id', 'exam_id', 'subject_id', 'marks_obtained', 'remarks'])
                ->where('exam_id', $exam->id)
                ->whereIn('student_id', $students->pluck('id'))
                ->get()
                ->keyBy('student_id');

            return $students->map(function ($student) use ($existingGrades) {
                $grade = $existingGrades->get($student->id);
                return [
                    'student_id' => $student->id,
                    'name' => $student->user->name,
                    'marks_obtained' => $grade ? $grade->marks_obtained : 0,
                    'remarks' => $grade ? $grade->remarks : '',
                ];
            });
        }, $this->cacheTTL);
    }

    public function getClassesForGrading(): Collection
    {
        return $this->remember('grades:classes', function () {
            return AcademicClass::query()
                ->select(['id', 'name', 'code'])
                ->with(['sections:id,academic_class_id,name'])
                ->get();
        }, $this->cacheTTL * 2);
    }

    public function saveGrades(Exam $exam, array $gradesData): void
    {
        foreach ($gradesData as $data) {
            Grade::updateOrCreate(
                [
                    'student_id' => $data['student_id'],
                    'exam_id' => $exam->id,
                    'subject_id' => $exam->subject_id,
                ],
                [
                    'marks_obtained' => $data['marks_obtained'],
                    'remarks' => $data['remarks'] ?? '',
                ]
            );
        }

        $this->clearGradeCache($exam->id);
    }

    public function getStudentGrades(Student $student): array
    {
        // This is a placeholder - actual implementation would query Grade model
        // For now returns mock data as in original controller
        return [
            [
                'subject' => 'Mathematics',
                'grade' => 'A',
                'score' => 92,
                'exam_date' => '2024-01-15'
            ],
            [
                'subject' => 'Science',
                'grade' => 'B+',
                'score' => 87,
                'exam_date' => '2024-01-20'
            ],
            [
                'subject' => 'English',
                'grade' => 'A-',
                'score' => 89,
                'exam_date' => '2024-01-25'
            ]
        ];
    }

    public function getStudentStats(Student $student): array
    {
        // This is a placeholder - actual implementation would calculate real stats
        return [
            'average_grade' => 'A-',
            'pass_rate' => 95,
            'total_students' => 1,
            'total_exams' => 3
        ];
    }

    public function clearGradeCache(?int $examId = null): void
    {
        if ($examId) {
            \Cache::forget("grades:exam:{$examId}");
        }
        \Cache::flush();
    }
}
