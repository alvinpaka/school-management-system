<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAttendanceRequest;
use App\Models\AcademicClass;
use App\Models\Attendance;
use App\Models\Section;
use App\Models\Student;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $classes = AcademicClass::with('sections')->get();
        
        $attendance = [];
        if ($request->has(['academic_class_id', 'section_id', 'date'])) {
            $attendance = Attendance::where('academic_class_id', $request->academic_class_id)
                ->where('section_id', $request->section_id)
                ->where('date', $request->date)
                ->with('student.user')
                ->get();
                
            if ($attendance->isEmpty()) {
                // Pre-populate with students from that class/section
                $students = Student::where('academic_class_id', $request->academic_class_id)
                    ->where('section_id', $request->section_id)
                    ->with('user')
                    ->get();
                    
                $attendance = $students->map(function ($student) use ($request) {
                    return [
                        'student_id' => $student->id,
                        'student' => $student,
                        'status' => 'present',
                        'remarks' => '',
                    ];
                });
            }
        }

        return Inertia::render('Attendance/Index', [
            'classes' => $classes,
            'attendanceData' => $attendance,
            'filters' => $request->only(['academic_class_id', 'section_id', 'date'])
        ]);
    }

    public function store(StoreAttendanceRequest $request)
    {
        foreach ($request->attendance as $data) {
            Attendance::updateOrCreate(
                [
                    'student_id' => $data['student_id'],
                    'academic_class_id' => $request->academic_class_id,
                    'section_id' => $request->section_id,
                    'date' => $request->date,
                ],
                [
                    'status' => $data['status'],
                    'remarks' => $data['remarks'] ?? '',
                ]
            );
        }

        return redirect()->back()->with('success', 'Attendance recorded successfully.');
    }
}
