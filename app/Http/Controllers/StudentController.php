<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\AcademicClass;
use App\Models\Section;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class StudentController extends Controller
{
    public function search(Request $request)
    {
        $search = $request->input('search');
        
        if (strlen($search) < 2) {
            return response()->json(['students' => []]);
        }
        
        $students = Student::with(['user', 'academicClass', 'section'])
            ->where(function ($q) use ($search) {
                $q->whereHas('user', function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', '%' . $search . '%')
                           ->orWhere('email', 'like', '%' . $search . '%');
                })
                ->orWhere('admission_number', 'like', '%' . $search . '%');
            })
            ->limit(10)
            ->get();
        
        return response()->json(['students' => $students]);
    }

    public function index()
    {
        $search = request('search');
        
        $query = Student::with(['user', 'academicClass', 'section']);
        
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', '%' . $search . '%')
                           ->orWhere('email', 'like', '%' . $search . '%');
                })
                ->orWhere('admission_number', 'like', '%' . $search . '%');
            });
        }
        
        $students = $query->get();
        
        return Inertia::render('Students/Index', [
            'students' => $students,
            'filters' => ['search' => $search]
        ]);
    }

    public function create()
    {
        return Inertia::render('Students/Create', [
            'classes' => AcademicClass::all(),
            'sections' => Section::all(),
        ]);
    }

    public function show(Student $student)
    {
        return Inertia::render('Students/Show', [
            'student' => $student->load(['user', 'academicClass', 'section'])
        ]);
    }

    public function store(StoreStudentRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole('student');

        Student::create([
            'user_id' => $user->id,
            'academic_class_id' => $request->academic_class_id,
            'section_id' => $request->section_id,
            'admission_number' => $request->admission_number,
            'date_of_birth' => $request->date_of_birth,
        ]);

        return redirect()->route('students.index')->with('success', 'Student created successfully.');
    }

    public function edit(Student $student)
    {
        return Inertia::render('Students/Edit', [
            'student' => $student->load('user'),
            'classes' => AcademicClass::all(),
            'sections' => Section::all(),
        ]);
    }

    public function update(UpdateStudentRequest $request, Student $student)
    {
        $student->user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($request->password) {
            $student->user->update(['password' => Hash::make($request->password)]);
        }

        $student->update([
            'academic_class_id' => $request->academic_class_id,
            'section_id' => $request->section_id,
            'admission_number' => $request->admission_number,
            'date_of_birth' => $request->date_of_birth,
        ]);

        return redirect()->route('students.index')->with('success', 'Student updated successfully.');
    }

    public function destroy(Student $student)
    {
        $student->user->delete(); // Cascades to student
        return redirect()->route('students.index')->with('success', 'Student deleted successfully.');
    }
}
