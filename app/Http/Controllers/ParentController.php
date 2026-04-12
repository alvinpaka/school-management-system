<?php

namespace App\Http\Controllers;

use App\Http\Resources\ParentResource;
use App\Models\ParentUser;
use App\Services\ParentService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ParentController extends Controller
{
    protected ParentService $parentService;

    public function __construct(ParentService $parentService)
    {
        $this->parentService = $parentService;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $parents = $this->parentService->getParentsList($search);
        
        return Inertia::render('Parents/Index', [
            'parents' => $parents,
            'filters' => ['search' => $search]
        ]);
    }

    public function create()
    {
        return Inertia::render('Parents/Create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'occupation' => 'nullable|string|max:255',
            'relationship_to_student' => 'required|string|max:50',
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:students,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo');
        }

        $parentUser = $this->parentService->createParent($data);

        return redirect()->route('parents.show', $parentUser->id)->with('success', 'Parent created successfully.');
    }

    public function show(ParentUser $parent)
    {
        // Check if parent is trying to access another parent's data
        if (auth()->user()->hasRole('parent')) {
            if (auth()->id() !== $parent->user_id) {
                abort(403, 'You are not authorized to view this record.');
            }
        }
        
        return Inertia::render('Parents/Show', [
            'parent' => $this->parentService->getParentDetail($parent)
        ]);
    }

    public function edit(ParentUser $parent)
    {
        // Check if parent is trying to access another parent's data
        if (auth()->user()->hasRole('parent')) {
            if (auth()->id() !== $parent->user_id) {
                abort(403, 'You are not authorized to edit this record.');
            }
        }
        
        return Inertia::render('Parents/Edit', [
            'parent' => $this->parentService->getParentDetail($parent)
        ]);
    }

    public function update(Request $request, ParentUser $parent)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $parent->user_id,
            'password' => 'nullable|string|min:8',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'occupation' => 'nullable|string|max:255',
            'relationship_to_student' => 'required|string|max:50',
            'student_ids' => 'nullable|array',
            'student_ids.*' => 'exists:students,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo');
        }

        $this->parentService->updateParent($parent, $data);

        // Handle AJAX requests
        if ($request->expectsJson()) {
            return response()->json([
                'redirect' => route('parents.show', $parent->id),
                'message' => 'Parent updated successfully.'
            ]);
        }

        return redirect()->route('parents.show', $parent->id)->with('success', 'Parent updated successfully.');
    }

    public function destroy(ParentUser $parent)
    {
        $this->parentService->deleteParent($parent);
        return redirect()->route('parents.index')->with('success', 'Parent deleted successfully.');
    }
}
