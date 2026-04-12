<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFeeRequest;
use App\Http\Requests\UpdateFeeRequest;
use App\Models\Fee;
use App\Services\FeeService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FeeController extends Controller
{
    protected FeeService $feeService;

    public function __construct(FeeService $feeService)
    {
        $this->feeService = $feeService;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $studentIds = null;
        
        if (auth()->user()->hasRole('parent')) {
            $studentIds = view()->shared('parentStudentIds', []);
        }
        
        $fees = $this->feeService->getFeesList($search, $studentIds);
        
        return Inertia::render('Fees/Index', [
            'fees' => $fees,
            'filters' => ['search' => $search]
        ]);
    }

    public function create()
    {
        $students = $this->feeService->getStudentsForSelect();
        return Inertia::render('Fees/Create', [
            'students' => $students
        ]);
    }

    public function store(StoreFeeRequest $request)
    {
        $this->feeService->createFee($request->validated());
        return redirect()->route('fees.index')->with('success', 'Fee recorded successfully.');
    }

    public function show(Fee $fee)
    {
        if (auth()->user()->hasRole('parent')) {
            $parentStudentIds = view()->shared('parentStudentIds', []);
            if (!empty($parentStudentIds) && !in_array($fee->student_id, $parentStudentIds)) {
                abort(403, 'You are not authorized to view this fee record.');
            }
        }
        
        return Inertia::render('Fees/Show', [
            'fee' => $this->feeService->getFeeDetail($fee)
        ]);
    }

    public function edit(Fee $fee)
    {
        if (auth()->user()->hasRole('parent')) {
            $parentStudentIds = view()->shared('parentStudentIds', []);
            if (!empty($parentStudentIds) && !in_array($fee->student_id, $parentStudentIds)) {
                abort(403, 'You are not authorized to edit this fee record.');
            }
        }
        
        return Inertia::render('Fees/Edit', [
            'fee' => $this->feeService->getFeeForEdit($fee)
        ]);
    }

    public function update(UpdateFeeRequest $request, Fee $fee)
    {
        $this->feeService->updateFee($fee, $request->validated());
        return redirect()->route('fees.index')->with('success', 'Fee updated successfully.');
    }

    public function destroy(Fee $fee)
    {
        $this->feeService->deleteFee($fee);
        return redirect()->route('fees.index')->with('success', 'Fee record deleted.');
    }
}
