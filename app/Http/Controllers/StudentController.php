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
        
        // Filter for parents - only show their children
        if (auth()->user()->hasRole('parent')) {
            $studentIds = view()->shared('parentStudentIds', []);
        }
        
        $students = $this->studentService->getStudentsList($search, $studentIds);
        
        return Inertia::render('Students/Index', [
            'students' => $students,
            'filters' => ['search' => $search]
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
