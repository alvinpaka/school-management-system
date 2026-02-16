<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExamRequest;
use App\Http\Requests\UpdateExamRequest;
use App\Models\Exam;
use App\Models\Subject;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ExamController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $query = Exam::with('subject');
        
        // Search functionality
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('status', 'like', "%{$search}%")
                  ->orWhere('exam_date', 'like', "%{$search}%")
                  ->orWhere('duration', 'like', "%{$search}%")
                  ->orWhereHas('subject', function($subQuery) use ($search) {
                      $subQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        $exams = $query->paginate(10);
        return Inertia::render('Exams/Index', [
            'exams' => $exams,
            'filters' => ['search' => $search]
        ]);
    }

    public function create()
    {
        $subjects = Subject::all();
        return Inertia::render('Exams/Create', [
            'subjects' => $subjects
        ]);
    }

    public function show(Exam $exam)
    {
        return Inertia::render('Exams/Show', [
            'exam' => $exam->load('subject')
        ]);
    }

    public function store(StoreExamRequest $request)
    {
        Exam::create($request->validated());
        return redirect()->route('exams.index')->with('success', 'Exam scheduled successfully.');
    }

    public function edit(Exam $exam)
    {
        $subjects = Subject::all();
        return Inertia::render('Exams/Edit', [
            'exam' => $exam,
            'subjects' => $subjects
        ]);
    }

    public function update(UpdateExamRequest $request, Exam $exam)
    {
        $exam->update($request->validated());
        return redirect()->route('exams.index')->with('success', 'Exam updated successfully.');
    }

    public function destroy(Exam $exam)
    {
        $exam->delete();
        return redirect()->route('exams.index')->with('success', 'Exam deleted successfully.');
    }
}
