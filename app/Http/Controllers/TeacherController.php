<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTeacherRequest;
use App\Http\Requests\UpdateTeacherRequest;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $query = Teacher::with('user');
        
        // Search functionality
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('employee_id', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('specialization', 'like', "%{$search}%")
                  ->orWhereHas('user', function($subQuery) use ($search) {
                      $subQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }
        
        // Filter for parents - only show teachers of their children
        if (auth()->user()->hasRole('parent')) {
            $parentStudents = view()->shared('parentStudents', collect());
            $classIds = $parentStudents->pluck('academic_class_id')->unique()->toArray();
            
            // Get teachers assigned to classes of parent's children
            $query->whereHas('classes', function ($q) use ($classIds) {
                $q->whereIn('academic_classes.id', $classIds);
            });
        }
        
        $teachers = $query->paginate(10);
        
        return Inertia::render('Teachers/Index', [
            'teachers' => $teachers,
            'filters' => ['search' => $search]
        ]);
    }

    public function create()
    {
        return Inertia::render('Teachers/Create');
    }

    public function show(Teacher $teacher)
    {
        $teacher->load(['user', 'classes.students']);
        
        // Calculate total students from all classes
        $totalStudents = 0;
        foreach ($teacher->classes as $class) {
            $totalStudents += $class->students->count();
        }
        
        $teacher->total_students = $totalStudents;
        
        return Inertia::render('Teachers/Show', [
            'teacher' => $teacher
        ]);
    }

    public function store(StoreTeacherRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole('teacher');

        // Handle photo upload
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoPath = $photo->store('photos', 'public');
            $user->update(['photo' => $photoPath]);
        }

        $teacher = Teacher::create([
            'user_id' => $user->id,
            'employee_id' => $request->employee_id,
            'phone' => $request->phone,
            'employment_type' => $request->employment_type,
            'status' => $request->status,
            'specialization' => $request->specialization,
            'qualification' => $request->qualification,
            'experience' => $request->experience,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'address' => $request->address,
            'joining_date' => $request->joining_date,
            'emergency_contact_name' => $request->emergency_contact_name,
            'emergency_contact_phone' => $request->emergency_contact_phone,
            'emergency_contact_relationship' => $request->emergency_contact_relationship,
            'blood_group' => $request->blood_group,
        ]);

        return redirect()->route('teachers.show', $teacher->id)->with('success', 'Teacher created successfully.');
    }

    public function edit(Teacher $teacher)
    {
        return Inertia::render('Teachers/Edit', [
            'teacher' => $teacher->load('user')
        ]);
    }

    public function update(UpdateTeacherRequest $request, Teacher $teacher)
    {
        $teacher->user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($request->password) {
            $teacher->user->update(['password' => Hash::make($request->password)]);
        }

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoPath = $photo->store('photos', 'public');
            $teacher->user->update(['photo' => $photoPath]);
        }

        $teacher->update([
            'employee_id' => $request->employee_id,
            'phone' => $request->phone,
            'employment_type' => $request->employment_type,
            'status' => $request->status,
            'specialization' => $request->specialization,
            'qualification' => $request->qualification,
            'experience' => $request->experience,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'address' => $request->address,
            'joining_date' => $request->joining_date,
            'emergency_contact_name' => $request->emergency_contact_name,
            'emergency_contact_phone' => $request->emergency_contact_phone,
            'emergency_contact_relationship' => $request->emergency_contact_relationship,
            'blood_group' => $request->blood_group,
        ]);

        return redirect()->route('teachers.show', $teacher->id)->with('success', 'Teacher updated successfully.');
    }

    public function destroy(Teacher $teacher)
    {
        $teacher->user->delete(); // Cascades to teacher
        return redirect()->route('teachers.index')->with('success', 'Teacher deleted successfully.');
    }
}
