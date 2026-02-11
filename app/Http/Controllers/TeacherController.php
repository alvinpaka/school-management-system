<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTeacherRequest;
use App\Http\Requests\UpdateTeacherRequest;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = Teacher::with('user')->get();
        return Inertia::render('Teachers/Index', [
            'teachers' => $teachers
        ]);
    }

    public function create()
    {
        return Inertia::render('Teachers/Create');
    }

    public function show(Teacher $teacher)
    {
        return Inertia::render('Teachers/Show', [
            'teacher' => $teacher->load('user')
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

        Teacher::create([
            'user_id' => $user->id,
            'employee_id' => $request->employee_id,
            'phone' => $request->phone,
            'specialization' => $request->specialization,
        ]);

        return redirect()->route('teachers.index')->with('success', 'Teacher created successfully.');
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

        $teacher->update([
            'employee_id' => $request->employee_id,
            'phone' => $request->phone,
            'specialization' => $request->specialization,
        ]);

        return redirect()->route('teachers.index')->with('success', 'Teacher updated successfully.');
    }

    public function destroy(Teacher $teacher)
    {
        $teacher->user->delete(); // Cascades to teacher
        return redirect()->route('teachers.index')->with('success', 'Teacher deleted successfully.');
    }
}
