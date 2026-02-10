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
}
