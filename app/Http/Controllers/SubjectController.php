<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubjectRequest;
use App\Http\Requests\UpdateSubjectRequest;
use App\Models\Subject;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $query = Subject::query();
        
        // Search functionality
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('type', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        // Filter for parents - only show subjects that their children study
        if (auth()->user()->hasRole('parent')) {
            $parentStudents = view()->shared('parentStudents', collect());
            $classIds = $parentStudents->pluck('academic_class_id')->unique()->toArray();
            
            // Get subjects that are taught in the parent's children's classes
            // This assumes there's a relationship between subjects and classes through teachers or curriculum
            // For now, we'll show all subjects since subjects are typically school-wide
            // In a real system, you might have a curriculum table linking subjects to classes
        }
        
        $subjects = $query->paginate(10);
        return Inertia::render('Subjects/Index', [
            'subjects' => $subjects,
            'filters' => ['search' => $search]
        ]);
    }

    public function create()
    {
        return Inertia::render('Subjects/Create');
    }

    public function show(Subject $subject)
    {
        return Inertia::render('Subjects/Show', [
            'subject' => $subject
        ]);
    }

    public function store(StoreSubjectRequest $request)
    {
        Subject::create($request->validated());
        return redirect()->route('subjects.index')->with('success', 'Subject created successfully.');
    }

    public function edit(Subject $subject)
    {
        return Inertia::render('Subjects/Edit', [
            'subject' => $subject
        ]);
    }

    public function update(UpdateSubjectRequest $request, Subject $subject)
    {
        $subject->update($request->validated());
        return redirect()->route('subjects.index')->with('success', 'Subject updated successfully.');
    }

    public function destroy(Subject $subject)
    {
        $subject->delete();
        return redirect()->route('subjects.index')->with('success', 'Subject deleted successfully.');
    }
}
