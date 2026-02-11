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
    public function index()
    {
        $classes = AcademicClass::with('sections')->get();
        return Inertia::render('AcademicClasses/Index', [
            'classes' => $classes
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
