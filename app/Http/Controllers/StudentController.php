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

    public function getAllStudents()
    {
        $students = Student::with('user')->get();
        return response()->json(['students' => $students]);
    }


    public function index()
    {
        $search = request('search');
        
        $query = Student::with(['user', 'academicClass', 'section']);
        
        // Filter for parents - only show their children
        if (auth()->user()->hasRole('parent')) {
            $parentStudentIds = view()->shared('parentStudentIds', []);
            if (!empty($parentStudentIds)) {
                $query->whereIn('id', $parentStudentIds);
            }
        }
        
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', '%' . $search . '%')
                           ->orWhere('email', 'like', '%' . $search . '%');
                })
                ->orWhere('admission_number', 'like', '%' . $search . '%');
            });
        }
        
        $students = $query->paginate(10);
        
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
        // Check if parent is trying to access their child's record
        if (auth()->user()->hasRole('parent')) {
            $parentStudentIds = view()->shared('parentStudentIds', []);
            if (!empty($parentStudentIds) && !in_array($student->id, $parentStudentIds)) {
                abort(403, 'You are not authorized to view this student\'s record.');
            }
        }
        
        return Inertia::render('Students/Show', [
            'student' => $student->load(['user', 'academicClass', 'section', 'parentUser.user'])
        ]);
    }

    public function store(StoreStudentRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        
        // Handle photo upload
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoPath = $photo->store('photos', 'public');
            $user->update(['photo' => $photoPath]);
        }
        
        // Generate unique roll number
        $rollNumber = 'ROLL' . date('Y') . str_pad(Student::count() + 1, 4, '0', STR_PAD_LEFT);
        
        Student::create([
            'user_id' => $user->id,
            'academic_class_id' => $request->academic_class_id,
            'section_id' => $request->section_id,
            'admission_number' => $request->admission_number,
            'roll_number' => $request->roll_number,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender ? ucfirst(strtolower($request->gender)) : null,
            'phone' => $request->phone,
            'address' => $request->address,
            'admission_date' => $request->admission_date,
            'status' => $request->status ? strtolower($request->status) : 'active',
            'parent_name' => $request->parent_name,
            'parent_email' => $request->parent_email,
            'parent_phone' => $request->parent_phone,
            'emergency_contact' => $request->emergency_contact,
            'blood_group' => $request->blood_group,
            'medical_conditions' => $request->medical_conditions,
            'previous_school' => $request->previous_school,
            'transfer_certificate' => $request->transfer_certificate,
        ]);
        
        $user->assignRole('student');
        
        return redirect()->route('students.show', $student->id)->with('success', 'Student created successfully.');
    }

    public function edit(Student $student)
    {
        return Inertia::render('Students/Edit', [
            'student' => $student->load(['user', 'parentUser.user']),
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
        
        // Handle photo upload
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoPath = $photo->store('photos', 'public');
            $student->user->update(['photo' => $photoPath]);
        }

        $student->update([
            'academic_class_id' => $request->academic_class_id,
            'section_id' => $request->section_id,
            'admission_number' => $request->admission_number,
            'roll_number' => $request->roll_number,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender ? ucfirst(strtolower($request->gender)) : null,
            'phone' => $request->phone,
            'address' => $request->address,
            'admission_date' => $request->admission_date,
            'status' => $request->status ? strtolower($request->status) : 'active',
            'parent_name' => $request->parent_name,
            'parent_email' => $request->parent_email,
            'parent_phone' => $request->parent_phone,
            'emergency_contact' => $request->emergency_contact,
            'blood_group' => $request->blood_group,
            'medical_conditions' => $request->medical_conditions,
            'previous_school' => $request->previous_school,
            'transfer_certificate' => $request->transfer_certificate,
        ]);

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
        $student->user->delete(); // Cascades to student
        return redirect()->route('students.index')->with('success', 'Student deleted successfully.');
    }
}
