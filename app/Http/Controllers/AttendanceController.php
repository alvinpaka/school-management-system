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
        $query = AcademicClass::with('sections');
        
        // Filter for parents - only show classes of their children
        if (auth()->user()->hasRole('parent')) {
            $parentStudents = view()->shared('parentStudents', collect());
            $classIds = $parentStudents->pluck('academic_class_id')->unique()->toArray();
            $query->whereIn('id', $classIds);
        }
        
        $classes = $query->get();
        
        $attendance = [];
        
        if (auth()->user()->hasRole(['admin', 'teacher'])) {
            if ($request->has(['academic_class_id', 'section_id', 'date'])) {
                $attendanceQuery = Attendance::where('academic_class_id', $request->academic_class_id)
                    ->where('section_id', $request->section_id)
                    ->where('date', $request->date)
                    ->with('student.user');
                    
                $attendance = $attendanceQuery->get();
                    
                if ($attendance->isEmpty()) {
                    // Pre-populate with students from that class/section
                    $studentsQuery = Student::where('academic_class_id', $request->academic_class_id)
                        ->where('section_id', $request->section_id)
                        ->with('user');
                        
                    $students = $studentsQuery->get();
                        
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
        } else {
            // Students and Parents: Fetch their direct attendance history
            $attendanceQuery = Attendance::with('student.user')
                ->orderBy('date', 'desc')
                ->take(30); // Last 30 attendance records
                
            if (auth()->user()->hasRole('parent')) {
                $parentStudentIds = view()->shared('parentStudentIds', []);
                if (!empty($parentStudentIds)) {
                    $attendanceQuery->whereIn('student_id', $parentStudentIds);
                }
            } elseif (auth()->user()->hasRole('student')) {
                if (auth()->user()->student) {
                    $attendanceQuery->where('student_id', auth()->user()->student->id);
                }
            }
            
            $attendance = $attendanceQuery->get();
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
