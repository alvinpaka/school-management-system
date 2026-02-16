<?php

namespace App\Http\Controllers;

use App\Models\ParentUser;
use App\Models\Student;
use App\Http\Resources\ParentResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class ParentController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $query = ParentUser::with('user');
        
        // Search functionality
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('phone', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhere('occupation', 'like', "%{$search}%")
                  ->orWhere('relationship_to_student', 'like', "%{$search}%")
                  ->orWhereHas('user', function($subQuery) use ($search) {
                      $subQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }
        
        $parents = $query->paginate(10);
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
        $request->validate([
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

        // Create user account
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoPath = $photo->store('photos', 'public');
            $user->update(['photo' => $photoPath]);
        }

        // Assign parent role
        $user->assignRole('parent');

        // Create parent record
        $parentUser = ParentUser::create([
            'user_id' => $user->id,
            'phone' => $request->phone,
            'address' => $request->address,
            'occupation' => $request->occupation,
            'relationship_to_student' => $request->relationship_to_student,
        ]);
        
        // Attach students to parent
        if ($request->has('student_ids')) {
            Student::whereIn('id', $request->student_ids)->update(['parent_user_id' => $parentUser->id]);
        }

        return redirect()->route('parents.show', $parentUser->id)->with('success', 'Parent created successfully.');
    }

    public function show(ParentUser $parent)
    {
        $parent = ParentUser::with(['user', 'students.user', 'students.academicClass', 'students.section'])->find($parent->id);
        
        return Inertia::render('Parents/Show', [
            'parent' => $parent
        ]);
    }

    public function edit(ParentUser $parent)
    {
        $parent = ParentUser::with(['user', 'students.user', 'students.academicClass', 'students.section'])->find($parent->id);
        return Inertia::render('Parents/Edit', [
            'parent' => $parent
        ]);
    }

    public function update(Request $request, ParentUser $parent)
    {
        try {
            $validated = $request->validate([
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
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        }

        // Update user information
        $parent->user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Update password if provided
        if ($request->password) {
            $parent->user->update(['password' => Hash::make($request->password)]);
        }

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoPath = $photo->store('photos', 'public');
            $parent->user->update(['photo' => $photoPath]);
        }

        // Update parent information
        $parent->update($request->only(['phone', 'address', 'occupation', 'relationship_to_student']));
        
        // Sync student relationships
        if ($request->has('student_ids')) {
            // Remove this parent from all students first
            Student::where('parent_user_id', $parent->id)->update(['parent_user_id' => null]);
            // Assign selected students to this parent
            Student::whereIn('id', $request->student_ids)->update(['parent_user_id' => $parent->id]);
        } else {
            // Remove parent from all students
            Student::where('parent_user_id', $parent->id)->update(['parent_user_id' => null]);
        }

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
        $parent->delete();
        return redirect()->route('parents.index')->with('success', 'Parent deleted successfully.');
    }
}
