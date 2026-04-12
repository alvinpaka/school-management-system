<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExamRequest;
use App\Http\Requests\UpdateExamRequest;
use App\Models\Exam;
use App\Services\ExamService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ExamController extends Controller
{
    protected ExamService $examService;

    public function __construct(ExamService $examService)
    {
        $this->examService = $examService;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $exams = $this->examService->getExamsList($search);
        
        return Inertia::render('Exams/Index', [
            'exams' => $exams,
            'filters' => ['search' => $search]
        ]);
    }

    public function create()
    {
        // Only admins and teachers can create exams
        if (!auth()->user()->hasRole(['admin', 'teacher'])) {
            abort(403, 'You are not authorized to create exams.');
        }
        
        $subjects = $this->examService->getSubjectsForSelect();
        return Inertia::render('Exams/Create', [
            'subjects' => $subjects
        ]);
    }

    public function show(Exam $exam)
    {
        return Inertia::render('Exams/Show', [
            'exam' => $this->examService->getExamDetail($exam)
        ]);
    }

    public function store(StoreExamRequest $request)
    {
        // Only admins and teachers can create exams
        if (!auth()->user()->hasRole(['admin', 'teacher'])) {
            abort(403, 'You are not authorized to create exams.');
        }
        
        $this->examService->createExam($request->validated());
        return redirect()->route('exams.index')->with('success', 'Exam scheduled successfully.');
    }

    public function edit(Exam $exam)
    {
        // Only admins and teachers can edit exams
        if (!auth()->user()->hasRole(['admin', 'teacher'])) {
            abort(403, 'You are not authorized to edit exams.');
        }
        
        $subjects = $this->examService->getSubjectsForSelect();
        return Inertia::render('Exams/Edit', [
            'exam' => $this->examService->getExamDetail($exam),
            'subjects' => $subjects
        ]);
    }

    public function update(UpdateExamRequest $request, Exam $exam)
    {
        // Only admins and teachers can update exams
        if (!auth()->user()->hasRole(['admin', 'teacher'])) {
            abort(403, 'You are not authorized to update exams.');
        }
        
        $this->examService->updateExam($exam, $request->validated());
        return redirect()->route('exams.index')->with('success', 'Exam updated successfully.');
    }

    public function destroy(Exam $exam)
    {
        // Only admins and teachers can delete exams
        if (!auth()->user()->hasRole(['admin', 'teacher'])) {
            abort(403, 'You are not authorized to delete exams.');
        }
        
        $this->examService->deleteExam($exam);
        return redirect()->route('exams.index')->with('success', 'Exam deleted successfully.');
    }
}
