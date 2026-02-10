<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExamRequest;
use App\Http\Requests\UpdateExamRequest;
use App\Models\Exam;
use App\Models\Subject;
use Inertia\Inertia;

class ExamController extends Controller
{
    public function index()
    {
        $exams = Exam::with('subject')->get();
        return Inertia::render('Exams/Index', [
            'exams' => $exams
        ]);
    }

    public function create()
    {
        $subjects = Subject::all();
        return Inertia::render('Exams/Create', [
            'subjects' => $subjects
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
