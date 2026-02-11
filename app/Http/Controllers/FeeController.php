<?php

namespace App\Http\Controllers;

use App\Models\Fee;
use App\Models\Student;
use App\Http\Requests\StoreFeeRequest;
use App\Http\Requests\UpdateFeeRequest;
use Inertia\Inertia;

class FeeController extends Controller
{
    public function index()
    {
        $fees = Fee::with('student.user')->latest()->paginate(10);
        return Inertia::render('Fees/Index', [
            'fees' => $fees
        ]);
    }

    public function create()
    {
        $students = Student::with('user')->get();
        return Inertia::render('Fees/Create', [
            'students' => $students
        ]);
    }

    public function store(StoreFeeRequest $request)
    {
        Fee::create($request->validated());
        return redirect()->route('fees.index')->with('success', 'Fee recorded successfully.');
    }

    public function show(Fee $fee)
    {
        $fee->load('student.user');
        return Inertia::render('Fees/Show', [
            'fee' => $fee
        ]);
    }

    public function edit(Fee $fee)
    {
        $fee->load('student.user');
        return Inertia::render('Fees/Edit', [
            'fee' => $fee
        ]);
    }

    public function update(UpdateFeeRequest $request, Fee $fee)
    {
        $fee->update($request->validated());
        return redirect()->route('fees.index')->with('success', 'Fee updated successfully.');
    }

    public function destroy(Fee $fee)
    {
        $fee->delete();
        return redirect()->route('fees.index')->with('success', 'Fee record deleted.');
    }
}
