<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAcademicClassRequest;
use App\Http\Requests\UpdateAcademicClassRequest;
use App\Models\AcademicClass;
use App\Services\AcademicClassService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AcademicClassController extends Controller
{
    protected AcademicClassService $classService;

    public function __construct(AcademicClassService $classService)
    {
        $this->classService = $classService;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $classIds = null;
        
        // Filter for parents - only show classes of their children
        if (auth()->user()->hasRole('parent')) {
            $parentStudents = view()->shared('parentStudents', collect());
            $classIds = $parentStudents->pluck('academic_class_id')->unique()->toArray();
        }
        
        $classes = $this->classService->getClassesList($search, $classIds);
        
        return Inertia::render('AcademicClasses/Index', [
            'classes' => $classes,
            'filters' => ['search' => $search]
        ]);
    }

    public function studentIndex(Request $request)
    {
        $user = $request->user();
        $role = null;
        $classIds = null;
        $studentClassId = null;
        
        if ($user->hasRole('student')) {
            $role = 'student';
            $studentClassId = $user->student?->academic_class_id;
        } elseif ($user->hasRole('teacher')) {
            $role = 'teacher';
        } elseif ($user->hasRole('parent')) {
            $role = 'parent';
            $parentStudents = view()->shared('parentStudents', collect());
            $classIds = $parentStudents->pluck('academic_class_id')->unique()->toArray();
        }
        
        $classes = $this->classService->getClassesForUser($role, $classIds, $studentClassId);
        
        return Inertia::render('AcademicClasses/Index', [
            'classes' => $classes
        ]);
    }

    public function create()
    {
        return Inertia::render('AcademicClasses/Create');
    }

    public function show(AcademicClass $class)
    {
        return Inertia::render('AcademicClasses/Show', [
            'academicClass' => $this->classService->getClassDetail($class)
        ]);
    }

    public function store(StoreAcademicClassRequest $request)
    {
        $this->classService->createClass($request->validated());

        return redirect()->route('classes.index')->with('success', 'Class created successfully.');
    }

    public function edit(AcademicClass $class)
    {
        return Inertia::render('AcademicClasses/Edit', [
            'academicClass' => $this->classService->getClassDetail($class)
        ]);
    }

    public function update(UpdateAcademicClassRequest $request, AcademicClass $class)
    {
        $this->classService->updateClass($class, $request->validated());

        return redirect()->route('classes.index')->with('success', 'Class updated successfully.');
    }

    public function destroy(AcademicClass $class)
    {
        $this->classService->deleteClass($class);
        return redirect()->route('classes.index')->with('success', 'Class deleted successfully.');
    }
}
