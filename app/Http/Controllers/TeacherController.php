<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTeacherRequest;
use App\Http\Requests\UpdateTeacherRequest;
use App\Models\Teacher;
use App\Services\TeacherService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;

class TeacherController extends Controller
{
    protected TeacherService $teacherService;

    public function __construct(TeacherService $teacherService)
    {
        $this->teacherService = $teacherService;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $classIds = null;
        
        // Filter for parents - only show teachers of their children
        if (auth()->user()->hasRole('parent')) {
            $parentStudents = view()->shared('parentStudents', collect());
            $classIds = $parentStudents->pluck('academic_class_id')->unique()->toArray();
        }
        
        $teachers = $this->teacherService->getTeachersList($search, $classIds);
        
        return Inertia::render('Teachers/Index', [
            'teachers' => $teachers,
            'filters' => ['search' => $search]
        ]);
    }

    public function create()
    {
        return Inertia::render('Teachers/Create');
    }

    public function show(Teacher $teacher)
    {
        // Check if parent is trying to access teacher - only allow if teacher teaches their children
        if (auth()->user()->hasRole('parent')) {
            $parentStudents = view()->shared('parentStudents', collect());
            $classIds = $parentStudents->pluck('academic_class_id')->unique()->toArray();
            
            // Check if this teacher teaches any of the parent's children's classes
            $teacherClassIds = $teacher->classes->pluck('id')->toArray();
            if (empty(array_intersect($teacherClassIds, $classIds))) {
                abort(403, 'You are not authorized to view this teacher\'s record.');
            }
        }

        // Get all available classes and subjects for assignment (for admin)
        $availableClasses = [];
        $availableSubjects = [];
        if (auth()->user()->hasRole('admin')) {
            $availableClasses = \App\Models\AcademicClass::query()
                ->select(['id', 'name', 'code'])
                ->with(['sections:id,name,academic_class_id'])
                ->orderBy('name')
                ->get()
                ->map(fn($class) => [
                    'id' => $class->id,
                    'name' => $class->name,
                    'code' => $class->code,
                    'sections' => $class->sections->map(fn($section) => [
                        'id' => $section->id,
                        'name' => $section->name,
                    ])->toArray(),
                ])
                ->toArray();

            $availableSubjects = \App\Models\Subject::query()
                ->select(['id', 'name', 'code', 'type'])
                ->orderBy('name')
                ->get()
                ->map(fn($subject) => [
                    'id' => $subject->id,
                    'name' => $subject->name,
                    'code' => $subject->code,
                    'type' => $subject->type,
                ])
                ->toArray();
        }
        
        return Inertia::render('Teachers/Show', [
            'teacher' => $this->teacherService->getTeacherDetail($teacher),
            'availableClasses' => $availableClasses,
            'availableSubjects' => $availableSubjects,
        ]);
    }

    public function store(StoreTeacherRequest $request)
    {
        $data = $request->validated();
        
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo');
        }
        
        $teacher = $this->teacherService->createTeacher($data);

        return redirect()->route('teachers.show', $teacher->id)->with('success', 'Teacher created successfully.');
    }

    public function edit(Teacher $teacher)
    {
        return Inertia::render('Teachers/Edit', [
            'teacher' => $this->teacherService->getTeacherForEdit($teacher)
        ]);
    }

    public function update(UpdateTeacherRequest $request, Teacher $teacher)
    {
        $data = $request->validated();
        
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo');
        }
        
        $this->teacherService->updateTeacher($teacher, $data);

        return redirect()->route('teachers.show', $teacher->id)->with('success', 'Teacher updated successfully.');
    }

    public function destroy(Teacher $teacher)
    {
        $this->teacherService->deleteTeacher($teacher);
        return redirect()->route('teachers.index')->with('success', 'Teacher deleted successfully.');
    }

    /**
     * Assign teacher to a class with optional subjects
     */
    public function assignClass(Request $request, Teacher $teacher)
    {
        $request->validate([
            'class_id' => 'required|exists:academic_classes,id',
            'section_id' => 'required|exists:sections,id',
            'is_class_teacher' => 'boolean',
            'subject_ids' => 'nullable|array',
            'subject_ids.*' => 'exists:subjects,id',
        ]);

        $classId = $request->input('class_id');
        $sectionId = $request->input('section_id');
        $isClassTeacher = $request->boolean('is_class_teacher');
        $subjectIds = $request->input('subject_ids', []);

        // Check if already assigned
        $existingAssignment = \DB::table('class_teachers')
            ->where('teacher_id', $teacher->id)
            ->where('academic_class_id', $classId)
            ->when($sectionId, fn($q) => $q->where('section_id', $sectionId))
            ->first();

        if ($existingAssignment) {
            // Update existing assignment - only updating is_class_teacher flag
            if ($existingAssignment->is_class_teacher !== $isClassTeacher) {
                // If setting as class teacher, remove any existing class teacher for this class-section
                if ($isClassTeacher && $sectionId) {
                    \DB::table('class_teachers')
                        ->where('academic_class_id', $classId)
                        ->where('section_id', $sectionId)
                        ->where('is_class_teacher', true)
                        ->update(['is_class_teacher' => false]);
                }

                \DB::table('class_teachers')
                    ->where('id', $existingAssignment->id)
                    ->update([
                        'is_class_teacher' => $isClassTeacher,
                        'updated_at' => now(),
                    ]);
            }
        } else {
            // If setting as class teacher, remove any existing class teacher for this class-section
            if ($isClassTeacher && $sectionId) {
                \DB::table('class_teachers')
                    ->where('academic_class_id', $classId)
                    ->where('section_id', $sectionId)
                    ->where('is_class_teacher', true)
                    ->update(['is_class_teacher' => false]);
            }

            \DB::table('class_teachers')->insert([
                'teacher_id' => $teacher->id,
                'academic_class_id' => $classId,
                'section_id' => $sectionId,
                'assigned_date' => now(),
                'status' => 'active',
                'is_class_teacher' => $isClassTeacher,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Sync subjects for this teacher, class, and section
        // Get currently assigned subjects for this class-section
        $currentlyAssignedSubjects = \DB::table('teacher_subject')
            ->where('teacher_id', $teacher->id)
            ->where('academic_class_id', $classId)
            ->where('section_id', $sectionId)
            ->pluck('subject_id')
            ->toArray();
        
        $subjectsToAdd = array_diff($subjectIds, $currentlyAssignedSubjects);
        $subjectsToRemove = array_diff($currentlyAssignedSubjects, $subjectIds);
        
        // Remove unchecked subjects for this specific class-section
        if (!empty($subjectsToRemove)) {
            \DB::table('teacher_subject')
                ->where('teacher_id', $teacher->id)
                ->where('academic_class_id', $classId)
                ->where('section_id', $sectionId)
                ->whereIn('subject_id', $subjectsToRemove)
                ->delete();
        }
        
        // Add new subjects
        if (!empty($subjectsToAdd)) {
            $subjectAssignments = [];
            foreach ($subjectsToAdd as $subjectId) {
                $subjectAssignments[] = [
                    'teacher_id' => $teacher->id,
                    'subject_id' => $subjectId,
                    'academic_class_id' => $classId,
                    'section_id' => $sectionId,
                    'assigned_date' => now(),
                    'status' => 'active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            \DB::table('teacher_subject')->insert($subjectAssignments);
        }

        // Clear cache
        Cache::forget("teacher:{$teacher->id}:detail");

        $subjectCount = count($subjectIds);
        $isExisting = isset($existingAssignment) && $existingAssignment;
        
        if ($isExisting) {
            $classTeacherMessage = $isClassTeacher 
                ? "Teacher updated as class teacher."
                : "Teacher class teacher status removed.";
            $subjectMessage = $subjectCount > 0 ? " Subjects updated: {$subjectCount} assigned." : "";
            $message = $classTeacherMessage . $subjectMessage;
        } else {
            $message = $isClassTeacher 
                ? "Teacher assigned as class teacher" . ($subjectCount > 0 ? " with {$subjectCount} subject(s)" : "") . " successfully."
                : "Teacher assigned to class" . ($subjectCount > 0 ? " with {$subjectCount} subject(s)" : "") . " successfully.";
        }

        return back()->with('success', $message);
    }

    /**
     * Remove teacher from a class and associated subjects
     */
    public function removeClass(Request $request, Teacher $teacher)
    {
        $request->validate([
            'class_id' => 'required|exists:academic_classes,id',
            'section_id' => 'nullable|exists:sections,id',
        ]);

        $classId = $request->input('class_id');
        $sectionId = $request->input('section_id');

        // Remove class assignment - filter by section if provided to avoid removing all sections
        $query = \DB::table('class_teachers')
            ->where('teacher_id', $teacher->id)
            ->where('academic_class_id', $classId);
        
        if ($sectionId) {
            $query->where('section_id', $sectionId);
        }
        
        $query->delete();

        // Also remove subject assignments for this class (and section if specified)
        $subjectQuery = \DB::table('teacher_subject')
            ->where('teacher_id', $teacher->id)
            ->where('academic_class_id', $classId);
        
        // Note: teacher_subject table may not have section_id, 
        // so we only filter by class. If you need section-specific subject removal,
        // the teacher_subject table would need a section_id column.
        
        $subjectQuery->delete();

        // Clear cache
        Cache::forget("teacher:{$teacher->id}:detail");

        return back()->with('success', 'Teacher removed from class and associated subjects successfully.');
    }

    /**
     * Assign subject to a teacher
     */
    public function assignSubject(Request $request, Teacher $teacher)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'academic_class_id' => 'nullable|exists:academic_classes,id',
        ]);

        $subjectId = $request->input('subject_id');
        $classId = $request->input('academic_class_id');

        // Check if already assigned
        $exists = \DB::table('teacher_subject')
            ->where('teacher_id', $teacher->id)
            ->where('subject_id', $subjectId)
            ->when($classId, fn($q) => $q->where('academic_class_id', $classId))
            ->exists();

        if ($exists) {
            return back()->with('error', 'Teacher is already assigned to this subject.');
        }

        \DB::table('teacher_subject')->insert([
            'teacher_id' => $teacher->id,
            'subject_id' => $subjectId,
            'academic_class_id' => $classId,
            'assigned_date' => now(),
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Clear cache
        Cache::forget("teacher:{$teacher->id}:detail");

        return back()->with('success', 'Subject assigned to teacher successfully.');
    }

    /**
     * Remove subject from a teacher
     */
    public function removeSubject(Request $request, Teacher $teacher)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
        ]);

        $subjectId = $request->input('subject_id');
        $classId = $request->input('academic_class_id');

        $query = \DB::table('teacher_subject')
            ->where('teacher_id', $teacher->id)
            ->where('subject_id', $subjectId);

        if ($classId) {
            $query->where('academic_class_id', $classId);
        }

        $query->delete();

        // Clear cache
        Cache::forget("teacher:{$teacher->id}:detail");

        return back()->with('success', 'Subject removed from teacher successfully.');
    }
}
