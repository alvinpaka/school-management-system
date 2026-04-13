<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGradeRequest;
use App\Models\Exam;
use App\Models\Student;
use App\Services\GradeService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GradeController extends Controller
{
    protected GradeService $gradeService;

    public function __construct(GradeService $gradeService)
    {
        $this->gradeService = $gradeService;
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $grades = [];
        $stats = [];

        // Students should not access grades module (view only via report cards)
        if ($user->hasRole('student')) {
            abort(403, 'You are not authorized to access grades.');
        } elseif ($user->hasRole('parent')) {
            $parentStudents = view()->shared('parentStudents', collect());
            if ($parentStudents->isNotEmpty()) {
                foreach ($parentStudents as $student) {
                    $grades[] = [
                        'student_name' => $student->user->name,
                        'student_id' => $student->id,
                        'grades' => $this->gradeService->getStudentGrades($student)
                    ];
                }
            }
        }
        
        return Inertia::render('Grades/Index', [
            'grades' => $grades,
            'stats' => $stats,
        ]);
    }
    
    public function enter(Exam $exam, Request $request)
    {
        // Only teachers and admins can enter grades
        if (!auth()->user()->hasRole(['admin', 'teacher'])) {
            abort(403, 'You are not authorized to enter grades.');
        }
        
        $classes = $this->gradeService->getClassesForGrading();
        $grades = collect([]);
        
        if ($request->has(['academic_class_id', 'section_id'])) {
            $grades = $this->gradeService->getGradesForExam(
                $exam,
                $request->academic_class_id,
                $request->section_id
            );
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
        $this->gradeService->saveGrades($exam, $request->grades);
        return redirect()->route('exams.index')->with('success', 'Grades recorded successfully.');
    }
}
