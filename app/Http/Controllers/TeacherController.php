<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTeacherRequest;
use App\Http\Requests\UpdateTeacherRequest;
use App\Models\Teacher;
use App\Services\TeacherService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TeacherController extends Controller
{
    protected TeacherService $teacherService;

    public function __construct(TeacherService $teacherService)
    {
        $this->teacherService = $teacherService;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $classIds = null;
        
        // Filter for parents - only show teachers of their children
        if (auth()->user()->hasRole('parent')) {
            $parentStudents = view()->shared('parentStudents', collect());
            $classIds = $parentStudents->pluck('academic_class_id')->unique()->toArray();
        }
        
        $teachers = $this->teacherService->getTeachersList($search, $classIds);
        
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
        // Check if parent is trying to access teacher - only allow if teacher teaches their children
        if (auth()->user()->hasRole('parent')) {
            $parentStudents = view()->shared('parentStudents', collect());
            $classIds = $parentStudents->pluck('academic_class_id')->unique()->toArray();
            
            // Check if this teacher teaches any of the parent's children's classes
            $teacherClassIds = $teacher->classes->pluck('id')->toArray();
            if (empty(array_intersect($teacherClassIds, $classIds))) {
                abort(403, 'You are not authorized to view this teacher\'s record.');
            }
        }
        
        return Inertia::render('Teachers/Show', [
            'teacher' => $this->teacherService->getTeacherDetail($teacher)
        ]);
    }

    public function store(StoreTeacherRequest $request)
    {
        $data = $request->validated();
        
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo');
        }
        
        $teacher = $this->teacherService->createTeacher($data);

        return redirect()->route('teachers.show', $teacher->id)->with('success', 'Teacher created successfully.');
    }

    public function edit(Teacher $teacher)
    {
        return Inertia::render('Teachers/Edit', [
            'teacher' => $this->teacherService->getTeacherForEdit($teacher)
        ]);
    }

    public function update(UpdateTeacherRequest $request, Teacher $teacher)
    {
        $data = $request->validated();
        
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo');
        }
        
        $this->teacherService->updateTeacher($teacher, $data);

        return redirect()->route('teachers.show', $teacher->id)->with('success', 'Teacher updated successfully.');
    }

    public function destroy(Teacher $teacher)
    {
        $this->teacherService->deleteTeacher($teacher);
        return redirect()->route('teachers.index')->with('success', 'Teacher deleted successfully.');
    }
}
