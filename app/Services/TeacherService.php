<?php

namespace App\Services;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class TeacherService extends BaseService
{
    protected int $cacheTTL = 600; // 10 minutes

    public function getTeachersList(?string $search = null, ?array $classIds = null, int $perPage = 10): LengthAwarePaginator
    {
        $cacheKey = $this->getCacheKey('teachers:list', [$search, $classIds, $perPage, request('page', 1)]);

        return $this->remember($cacheKey, function () use ($search, $classIds, $perPage) {
            $query = Teacher::query()
                ->select([
                    'id', 'user_id', 'employee_id', 'phone', 'specialization',
                    'employment_type', 'status', 'qualification', 'experience',
                    'joining_date', 'gender'
                ])
                ->with(['user:id,name,email,photo', 'user.roles:id,name']);

            // Search functionality
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('employee_id', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%")
                      ->orWhere('specialization', 'like', "%{$search}%")
                      ->orWhereHas('user', function($subQuery) use ($search) {
                          $subQuery->where('name', 'like', "%{$search}%")
                                   ->orWhere('email', 'like', "%{$search}%");
                      });
                });
            }

            // Filter by class IDs if provided (for parent view)
            if ($classIds && !empty($classIds)) {
                $query->whereHas('classes', function ($q) use ($classIds) {
                    $q->whereIn('academic_classes.id', $classIds);
                });
            }

            $paginator = $query->latest()->paginate($perPage);
            
            // Transform to include role
            $paginator->getCollection()->transform(function ($teacher) {
                $teacher->role = $teacher->user->roles->first()?->name ?? 'teacher';
                return $teacher;
            });
            
            return $paginator;
        }, $this->cacheTTL);
    }

    public function getTeacherDetail(Teacher $teacher): array
    {
        return $this->remember("teacher:{$teacher->id}:detail", function () use ($teacher) {
            $teacher->load([
                'user:id,name,email,phone,photo,address',
                'user.roles:id,name',
                'classes:id,name,code',
                'classes.sections:id,name,academic_class_id',
                'subjects:id,name,code,type'
            ]);

            // Calculate total students efficiently
            $totalStudents = $teacher->classes->sum(fn($class) => $class->students()->count());

            // Get unique class assignments with section info from pivot
            $classAssignments = [];
            foreach ($teacher->classes as $class) {
                $sectionId = $class->pivot->section_id ?? null;
                $sectionName = null;
                if ($sectionId && $class->sections) {
                    $section = $class->sections->firstWhere('id', $sectionId);
                    $sectionName = $section?->name;
                }
                $classAssignments[] = [
                    'id' => $class->id,
                    'name' => $class->name,
                    'code' => $class->code,
                    'section_id' => $sectionId,
                    'section_name' => $sectionName,
                    'is_class_teacher' => $class->pivot->is_class_teacher ?? false,
                ];
            }

            // Get user roles
            $userRoles = $teacher->user->roles->pluck('name')->toArray();
            $primaryRole = $userRoles[0] ?? 'teacher';

            return [
                'id' => $teacher->id,
                'user_id' => $teacher->user_id,
                'employee_id' => $teacher->employee_id,
                'phone' => $teacher->phone,
                'employment_type' => $teacher->employment_type,
                'status' => $teacher->status,
                'specialization' => $teacher->specialization,
                'qualification' => $teacher->qualification,
                'experience' => $teacher->experience,
                'date_of_birth' => $teacher->date_of_birth,
                'gender' => $teacher->gender,
                'address' => $teacher->address,
                'joining_date' => $teacher->joining_date,
                'emergency_contact_name' => $teacher->emergency_contact_name,
                'emergency_contact_phone' => $teacher->emergency_contact_phone,
                'emergency_contact_relationship' => $teacher->emergency_contact_relationship,
                'blood_group' => $teacher->blood_group,
                'total_students' => $totalStudents,
                'role' => $primaryRole,
                'roles' => $userRoles,
                'user' => [
                    'id' => $teacher->user->id,
                    'name' => $teacher->user->name,
                    'email' => $teacher->user->email,
                    'phone' => $teacher->user->phone,
                    'photo' => $teacher->user->photo,
                    'address' => $teacher->user->address,
                ],
                'classes' => $classAssignments,
                'subjects' => $teacher->subjects->map(fn($subject) => [
                    'id' => $subject->id,
                    'name' => $subject->name,
                    'code' => $subject->code,
                    'type' => $subject->type,
                    'academic_class_id' => $subject->pivot->academic_class_id,
                    'section_id' => $subject->pivot->section_id,
                ])->toArray(),
            ];
        }, $this->cacheTTL);
    }

    public function getTeacherForEdit(Teacher $teacher): array
    {
        $teacher->load(['user:id,name,email,phone,photo,address,date_of_birth,gender']);

        return [
            'id' => $teacher->id,
            'user_id' => $teacher->user_id,
            'employee_id' => $teacher->employee_id,
            'phone' => $teacher->phone,
            'employment_type' => $teacher->employment_type,
            'status' => $teacher->status,
            'specialization' => $teacher->specialization,
            'qualification' => $teacher->qualification,
            'experience' => $teacher->experience,
            'date_of_birth' => $teacher->date_of_birth,
            'gender' => $teacher->gender,
            'address' => $teacher->address,
            'joining_date' => $teacher->joining_date,
            'emergency_contact_name' => $teacher->emergency_contact_name,
            'emergency_contact_phone' => $teacher->emergency_contact_phone,
            'emergency_contact_relationship' => $teacher->emergency_contact_relationship,
            'blood_group' => $teacher->blood_group,
            'user' => [
                'id' => $teacher->user->id,
                'name' => $teacher->user->name,
                'email' => $teacher->user->email,
                'phone' => $teacher->user->phone,
                'photo' => $teacher->user->photo,
                'address' => $teacher->user->address,
                'date_of_birth' => $teacher->user->date_of_birth,
                'gender' => $teacher->user->gender,
            ],
        ];
    }

    public function createTeacher(array $data): Teacher
    {
        // Create user
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $user->assignRole($data['role'] ?? 'teacher');

        // Handle photo upload
        if (!empty($data['photo'])) {
            $photoPath = $data['photo']->store('photos', 'public');
            $user->update(['photo' => $photoPath]);
        }

        // Create teacher record
        $teacher = Teacher::create([
            'user_id' => $user->id,
            'employee_id' => $data['employee_id'],
            'phone' => $data['phone'] ?? null,
            'employment_type' => $data['employment_type'],
            'status' => $data['status'] ?? 'active',
            'specialization' => $data['specialization'] ?? null,
            'qualification' => $data['qualification'] ?? null,
            'experience' => $data['experience'] ?? null,
            'date_of_birth' => $data['date_of_birth'] ?? null,
            'gender' => $data['gender'] ?? null,
            'address' => $data['address'] ?? null,
            'joining_date' => $data['joining_date'] ?? null,
            'emergency_contact_name' => $data['emergency_contact_name'] ?? null,
            'emergency_contact_phone' => $data['emergency_contact_phone'] ?? null,
            'emergency_contact_relationship' => $data['emergency_contact_relationship'] ?? null,
            'blood_group' => $data['blood_group'] ?? null,
        ]);

        $this->clearTeacherCache();

        return $teacher;
    }

    public function updateTeacher(Teacher $teacher, array $data): Teacher
    {
        // Update user
        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
        ];

        if (!empty($data['password'])) {
            $userData['password'] = Hash::make($data['password']);
        }

        // Handle photo upload
        if (!empty($data['photo'])) {
            $photoPath = $data['photo']->store('photos', 'public');
            $userData['photo'] = $photoPath;
        }

        $teacher->user->update($userData);

        // Update role if changed
        if (!empty($data['role'])) {
            $teacher->user->syncRoles([$data['role']]);
        }

        // Update teacher record
        $teacher->update([
            'employee_id' => $data['employee_id'],
            'phone' => $data['phone'] ?? null,
            'employment_type' => $data['employment_type'],
            'status' => $data['status'] ?? 'active',
            'specialization' => $data['specialization'] ?? null,
            'qualification' => $data['qualification'] ?? null,
            'experience' => $data['experience'] ?? null,
            'date_of_birth' => $data['date_of_birth'] ?? null,
            'gender' => $data['gender'] ?? null,
            'address' => $data['address'] ?? null,
            'joining_date' => $data['joining_date'] ?? null,
            'emergency_contact_name' => $data['emergency_contact_name'] ?? null,
            'emergency_contact_phone' => $data['emergency_contact_phone'] ?? null,
            'emergency_contact_relationship' => $data['emergency_contact_relationship'] ?? null,
            'blood_group' => $data['blood_group'] ?? null,
        ]);

        $this->clearTeacherCache($teacher->id);

        return $teacher->fresh();
    }

    public function deleteTeacher(Teacher $teacher): void
    {
        $teacher->user->delete(); // Cascades to teacher
        $this->clearTeacherCache($teacher->id);
    }

    public function clearTeacherCache(?int $teacherId = null): void
    {
        if ($teacherId) {
            Cache::forget("teacher:{$teacherId}:detail");
        }
        // Clear list caches
        Cache::flush();
    }
}
