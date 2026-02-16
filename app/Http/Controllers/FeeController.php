<?php

namespace App\Http\Controllers;

use App\Models\Fee;
use App\Models\Student;
use App\Http\Requests\StoreFeeRequest;
use App\Http\Requests\UpdateFeeRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FeeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $query = Fee::with('student.user')->latest();
        
        // Search functionality
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('fee_type', 'like', "%{$search}%")
                  ->orWhere('status', 'like', "%{$search}%")
                  ->orWhere('amount', 'like', "%{$search}%")
                  ->orWhereHas('student.user', function($subQuery) use ($search) {
                      $subQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        // Filter for parents - only show fees for their children
        if (auth()->user()->hasRole('parent')) {
            $parentStudentIds = view()->shared('parentStudentIds', []);
            if (!empty($parentStudentIds)) {
                $query->whereIn('student_id', $parentStudentIds);
            }
        }
        
        $fees = $query->paginate(10);
        return Inertia::render('Fees/Index', [
            'fees' => $fees,
            'filters' => ['search' => $search]
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
        // Check if parent is trying to access their child's fee
        if (auth()->user()->hasRole('parent')) {
            $parentStudentIds = view()->shared('parentStudentIds', []);
            if (!empty($parentStudentIds) && !in_array($fee->student_id, $parentStudentIds)) {
                abort(403, 'You are not authorized to view this fee record.');
            }
        }
        
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
