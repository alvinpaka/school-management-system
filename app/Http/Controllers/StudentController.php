<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\Student;
use App\Services\StudentService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StudentController extends Controller
{
    protected StudentService $studentService;

    public function __construct(StudentService $studentService)
    {
        $this->studentService = $studentService;
    }

    public function search(Request $request)
    {
        $search = $request->input('search');
        
        $students = $this->studentService->searchStudents($search, 10);
        
        return response()->json(['students' => $students]);
    }

    public function getAllStudents()
    {
        $students = $this->studentService->getAllStudentsForSelect();
        return response()->json(['students' => $students]);
    }

    public function index()
    {
        $search = request('search');
        $studentIds = null;
        $classIds = null;
        $sectionIds = null;
        $canEnroll = true;
        $isClassTeacher = false;

        $user = auth()->user();

        // Filter for parents - only show their children
        if ($user->hasRole('parent')) {
            $studentIds = view()->shared('parentStudentIds', []);
        }

        // Filter for teachers - only show students in their assigned classes/sections
        if ($user->hasRole('teacher') && !$user->hasRole('admin')) {
            $teacher = \App\Models\Teacher::where('user_id', $user->id)->first();
            if ($teacher) {
                // Check if this teacher is a class teacher (form teacher) for any class-section
                $classTeacherAssignments = \DB::table('class_teachers')
                    ->where('teacher_id', $teacher->id)
                    ->where('status', 'active')
                    ->where('is_class_teacher', true)
                    ->get();
                
                if ($classTeacherAssignments->isNotEmpty()) {
                    // Class teacher sees ALL students in their assigned class-section(s)
                    $isClassTeacher = true;
                    $classIds = $classTeacherAssignments->pluck('academic_class_id')->unique()->toArray();
                    $sectionIds = $classTeacherAssignments->pluck('section_id')->filter()->unique()->toArray();
                } else {
                    // Subject teacher only sees students in classes/sections where they teach subjects
                    $subjectAssignments = $teacher->subjects()
                        ->wherePivot('status', 'active')
                        ->get();
                    
                    $classIds = $subjectAssignments->pluck('pivot.academic_class_id')->filter()->unique()->toArray();
                    
                    // If no class-specific assignments, fall back to all their class assignments
                    if (empty($classIds)) {
                        $classAssignments = \DB::table('class_teachers')
                            ->where('teacher_id', $teacher->id)
                            ->where('status', 'active')
                            ->get();
                        $classIds = $classAssignments->pluck('academic_class_id')->unique()->toArray();
                        $sectionIds = $classAssignments->pluck('section_id')->filter()->unique()->toArray();
                    }
                }
            }
            // Teachers cannot enroll students through this interface
            $canEnroll = false;
        }

        $students = $this->studentService->getStudentsList($search, $studentIds, $classIds, $sectionIds);
        $formOptions = $this->studentService->getFormOptions();

        return Inertia::render('Students/Index', [
            'students' => $students,
            'classes' => $formOptions['classes'],
            'sections' => $formOptions['sections'],
            'filters' => ['search' => $search],
            'canEnroll' => $canEnroll,
            'isClassTeacher' => $isClassTeacher,
        ]);
    }

    public function create()
    {
        $options = $this->studentService->getFormOptions();
        
        return Inertia::render('Students/Create', $options);
    }

    public function show(Student $student)
    {
        // Check if parent is trying to access their child's record
        if (auth()->user()->hasRole('parent')) {
            $parentStudentIds = view()->shared('parentStudentIds', []);
            if (!empty($parentStudentIds) && !in_array($student->id, $parentStudentIds)) {
                abort(403, 'You are not authorized to view this student\'s record.');
            }
        }
        
        return Inertia::render('Students/Show', [
            'student' => $this->studentService->getStudentDetail($student)
        ]);
    }

    public function store(StoreStudentRequest $request)
    {
        $data = $request->validated();
        
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo');
        }
        
        $student = $this->studentService->createStudent($data);
        
        return redirect()->route('students.show', $student->id)->with('success', 'Student created successfully.');
    }

    public function edit(Student $student)
    {
        $options = $this->studentService->getFormOptions();
        
        return Inertia::render('Students/Edit', array_merge([
            'student' => $this->studentService->getStudentForEdit($student),
        ], $options));
    }

    public function update(UpdateStudentRequest $request, Student $student)
    {
        $data = $request->validated();
        
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo');
        }
        
        $this->studentService->updateStudent($student, $data);

        // Handle AJAX requests
        if ($request->expectsJson()) {
            return response()->json([
                'redirect' => route('students.show', $student->id),
                'message' => 'Student updated successfully.'
            ]);
        }

        return redirect()->route('students.show', $student->id)->with('success', 'Student updated successfully.');
    }

    public function destroy(Student $student)
    {
        $this->studentService->deleteStudent($student);
        return redirect()->route('students.index')->with('success', 'Student deleted successfully.');
    }
}
