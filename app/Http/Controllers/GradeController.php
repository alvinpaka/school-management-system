<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGradeRequest;
use App\Models\Exam;
use App\Models\Grade;
use App\Models\Student;
use App\Models\AcademicClass;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GradeController extends Controller
{
    /**
     * Display the grades index page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        // Get the authenticated user
        $user = $request->user();
        
        // Initialize grades data
        $grades = [];
        $stats = [];
        
        // If user is a student, get their grades
        if ($user->hasRole('student')) {
            $student = $user->student;
            if ($student) {
                // TODO: Get actual grades data for the student
                $grades = $this->getStudentGrades($student);
                $stats = $this->getStudentStats($student);
            }
        }
        // If user is a teacher, get grades for their classes
        elseif ($user->hasRole('teacher')) {
            $teacher = $user->teacher;
            if ($teacher) {
                // TODO: Get grades for classes taught by this teacher
                $grades = [];
                $stats = [];
            }
        }
        // If user is admin, get all grades
        elseif ($user->hasRole('admin')) {
            // TODO: Get all grades
            $grades = [];
            $stats = [];
        }
        
        return Inertia::render('Grades/Index', [
            'grades' => $grades,
            'stats' => $stats,
        ]);
    }
    
    public function enter(Exam $exam, Request $request)
    {
        $classes = AcademicClass::with('sections')->get();
        
        $grades = [];
        if ($request->has(['academic_class_id', 'section_id'])) {
            $students = Student::where('academic_class_id', $request->academic_class_id)
                ->where('section_id', $request->section_id)
                ->with('user')
                ->get();
                
            $existingGrades = Grade::where('exam_id', $exam->id)
                ->whereIn('student_id', $students->pluck('id'))
                ->get()
                ->keyBy('student_id');
                
            $grades = $students->map(function ($student) use ($existingGrades) {
                $grade = $existingGrades->get($student->id);
                return [
                    'student_id' => $student->id,
                    'name' => $student->user->name,
                    'marks_obtained' => $grade ? $grade->marks_obtained : 0,
                    'remarks' => $grade ? $grade->remarks : '',
                ];
            });
        }

        return Inertia::render('Grades/Enter', [
            'exam' => $exam->load('subject'),
            'classes' => $classes,
            'studentsData' => $grades,
            'filters' => $request->only(['academic_class_id', 'section_id'])
        ]);
    }

    public function store(StoreGradeRequest $request, Exam $exam)
    {
        foreach ($request->grades as $data) {
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

        return redirect()->route('exams.index')->with('success', 'Grades recorded successfully.');
    }
    
    /**
     * Get grades for a specific student.
     *
     * @param  \App\Models\Student  $student
     * @return array
     */
    private function getStudentGrades($student)
    {
        // TODO: Implement actual grades logic
        // This should return the student's grades across all subjects
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
    
    /**
     * Get grade statistics for a specific student.
     *
     * @param  \App\Models\Student  $student
     * @return array
     */
    private function getStudentStats($student)
    {
        // TODO: Implement actual stats logic
        return [
            'average_grade' => 'A-',
            'pass_rate' => 95,
            'total_students' => 1,
            'total_exams' => 3
        ];
    }
}
