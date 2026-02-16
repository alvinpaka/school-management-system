<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAcademicClassRequest;
use App\Http\Requests\UpdateAcademicClassRequest;
use App\Models\AcademicClass;
use App\Models\Section;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AcademicClassController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $query = AcademicClass::with('sections');
        
        // Search functionality
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhereHas('sections', function($subQuery) use ($search) {
                      $subQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        // Filter for parents - only show classes of their children
        if (auth()->user()->hasRole('parent')) {
            $parentStudents = view()->shared('parentStudents', collect());
            $classIds = $parentStudents->pluck('academic_class_id')->unique()->toArray();
            $query->whereIn('id', $classIds);
        }
        
        $classes = $query->paginate(10);
        return Inertia::render('AcademicClasses/Index', [
            'classes' => $classes,
            'filters' => ['search' => $search]
        ]);
    }

    public function studentIndex(Request $request)
    {
        $user = $request->user();
        $classes = collect([]);
        
        // If user is a student, only show their class
        if ($user->hasRole('student')) {
            $student = $user->student;
            if ($student) {
                $classes = collect([$student->academicClass]);
            }
        }
        // If user is teacher, show their assigned classes
        elseif ($user->hasRole('teacher')) {
            // TODO: Get classes assigned to this teacher
            $classes = AcademicClass::with('sections')->get();
        }
        // If user is parent, show their children's classes
        elseif ($user->hasRole('parent')) {
            $parentStudents = view()->shared('parentStudents', collect());
            $classIds = $parentStudents->pluck('academic_class_id')->unique()->toArray();
            $classes = AcademicClass::with('sections')->whereIn('id', $classIds)->get();
        }
        
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
            'academicClass' => $class->load('sections')
        ]);
    }

    public function store(StoreAcademicClassRequest $request)
    {
        $class = AcademicClass::create([
            'name' => $request->name,
            'code' => $request->code,
        ]);

        if ($request->sections) {
            foreach ($request->sections as $sectionName) {
                Section::create([
                    'academic_class_id' => $class->id,
                    'name' => $sectionName,
                ]);
            }
        }

        return redirect()->route('classes.index')->with('success', 'Class created successfully.');
    }

    public function edit(AcademicClass $class)
    {
        return Inertia::render('AcademicClasses/Edit', [
            'academicClass' => $class->load('sections')
        ]);
    }

    public function update(UpdateAcademicClassRequest $request, AcademicClass $class)
    {
        $class->update([
            'name' => $request->name,
            'code' => $request->code,
        ]);

        // Simple sync for sections: delete all and recreate (in a more advanced system we'd sync by ID)
        $class->sections()->delete();

        if ($request->sections) {
            foreach ($request->sections as $sectionName) {
                Section::create([
                    'academic_class_id' => $class->id,
                    'name' => $sectionName,
                ]);
            }
        }

        return redirect()->route('classes.index')->with('success', 'Class updated successfully.');
    }

    public function destroy(AcademicClass $class)
    {
        $class->delete(); // Sections will be deleted via cascade if set up, or manually
        return redirect()->route('classes.index')->with('success', 'Class deleted successfully.');
    }
}
