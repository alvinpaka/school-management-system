<?php

namespace App\Services;

use App\Models\AcademicClass;
use App\Models\Section;
use App\Models\Student;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class StudentService extends BaseService
{
    protected int $cacheTTL = 600; // 10 minutes

    public function getStudentsList(?string $search = null, ?array $studentIds = null, ?array $classIds = null, ?array $sectionIds = null, int $perPage = 10): LengthAwarePaginator
    {
        $cacheKey = $this->getCacheKey('students:list', [$search, $studentIds, $classIds, $sectionIds, $perPage, request('page', 1)]);

        return $this->remember($cacheKey, function () use ($search, $studentIds, $classIds, $sectionIds, $perPage) {
            $query = Student::query()
                ->select([
                    'id', 'user_id', 'academic_class_id', 'section_id', 'parent_user_id',
                    'admission_number', 'roll_number', 'date_of_birth', 'gender', 'phone',
                    'address', 'admission_date', 'status'
                ])
                ->with([
                    'user:id,name,email,photo',
                    'academicClass:id,name,code',
                    'section:id,name'
                ]);

            // Filter by specific student IDs (for parent view)
            if ($studentIds && !empty($studentIds)) {
                $query->whereIn('id', $studentIds);
            }

            // Filter by class IDs (for teacher view - show only students in their classes)
            if ($classIds && !empty($classIds)) {
                $query->whereIn('academic_class_id', $classIds);
            }

            // Filter by section IDs (for teacher view - show only students in their assigned sections)
            if ($sectionIds && !empty($sectionIds)) {
                $query->whereIn('section_id', $sectionIds);
            }

            // Search functionality
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('user', function ($subQuery) use ($search) {
                        $subQuery->where('name', 'like', '%' . $search . '%')
                               ->orWhere('email', 'like', '%' . $search . '%');
                    })
                    ->orWhere('admission_number', 'like', '%' . $search . '%');
                });
            }

            return $query->latest()->paginate($perPage);
        }, $this->cacheTTL);
    }

    public function searchStudents(string $search, int $limit = 10): Collection
    {
        if (strlen($search) < 2) {
            return collect();
        }

        return Student::query()
            ->select(['id', 'user_id', 'academic_class_id', 'section_id', 'admission_number', 'roll_number'])
            ->with([
                'user:id,name,email,photo',
                'academicClass:id,name,code',
                'section:id,name'
            ])
            ->where(function ($q) use ($search) {
                $q->whereHas('user', function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', '%' . $search . '%')
                           ->orWhere('email', 'like', '%' . $search . '%');
                })
                ->orWhere('admission_number', 'like', '%' . $search . '%');
            })
            ->limit($limit)
            ->get();
    }

    public function getAllStudentsForSelect(): Collection
    {
        return $this->remember('students:all:select', function () {
            return Student::query()
                ->select(['id', 'user_id', 'admission_number'])
                ->with(['user:id,name,email'])
                ->get()
                ->map(fn($student) => [
                    'id' => $student->id,
                    'admission_number' => $student->admission_number,
                    'user' => [
                        'id' => $student->user->id,
                        'name' => $student->user->name,
                        'email' => $student->user->email,
                    ],
                ]);
        }, $this->cacheTTL);
    }

    public function getStudentDetail(Student $student): array
    {
        return $this->remember("student:{$student->id}:detail", function () use ($student) {
            $student->load([
                'user:id,name,email,photo,phone,address,date_of_birth,gender',
                'academicClass:id,name,code',
                'section:id,name',
                'parentUser:id,user_id,phone,address',
                'parentUser.user:id,name,email,phone'
            ]);

            return [
                'id' => $student->id,
                'user_id' => $student->user_id,
                'academic_class_id' => $student->academic_class_id,
                'section_id' => $student->section_id,
                'admission_number' => $student->admission_number,
                'roll_number' => $student->roll_number,
                'date_of_birth' => $student->date_of_birth,
                'gender' => $student->gender,
                'phone' => $student->phone,
                'address' => $student->address,
                'admission_date' => $student->admission_date,
                'status' => $student->status,
                'parent_name' => $student->parent_name,
                'parent_email' => $student->parent_email,
                'parent_phone' => $student->parent_phone,
                'emergency_contact' => $student->emergency_contact,
                'blood_group' => $student->blood_group,
                'medical_conditions' => $student->medical_conditions,
                'previous_school' => $student->previous_school,
                'transfer_certificate' => $student->transfer_certificate,
                'user' => [
                    'id' => $student->user->id,
                    'name' => $student->user->name,
                    'email' => $student->user->email,
                    'photo' => $student->user->photo,
                    'phone' => $student->user->phone,
                    'address' => $student->user->address,
                    'date_of_birth' => $student->user->date_of_birth,
                    'gender' => $student->user->gender,
                ],
                'academicClass' => $student->academicClass ? [
                    'id' => $student->academicClass->id,
                    'name' => $student->academicClass->name,
                    'code' => $student->academicClass->code,
                ] : null,
                'section' => $student->section ? [
                    'id' => $student->section->id,
                    'name' => $student->section->name,
                ] : null,
                'parentUser' => $student->parentUser ? [
                    'id' => $student->parentUser->id,
                    'user_id' => $student->parentUser->user_id,
                    'phone' => $student->parentUser->phone,
                    'address' => $student->parentUser->address,
                    'user' => [
                        'id' => $student->parentUser->user->id,
                        'name' => $student->parentUser->user->name,
                        'email' => $student->parentUser->user->email,
                        'phone' => $student->parentUser->user->phone,
                    ],
                ] : null,
            ];
        }, $this->cacheTTL);
    }

    public function getStudentForEdit(Student $student): array
    {
        $student->load([
            'user:id,name,email,photo,phone,address,date_of_birth,gender',
            'parentUser:id,user_id,phone,address',
            'parentUser.user:id,name,email,phone'
        ]);

        return [
            'id' => $student->id,
            'user_id' => $student->user_id,
            'academic_class_id' => $student->academic_class_id,
            'section_id' => $student->section_id,
            'admission_number' => $student->admission_number,
            'roll_number' => $student->roll_number,
            'date_of_birth' => $student->date_of_birth,
            'gender' => $student->gender,
            'phone' => $student->phone,
            'address' => $student->address,
            'admission_date' => $student->admission_date,
            'status' => $student->status,
            'parent_name' => $student->parent_name,
            'parent_email' => $student->parent_email,
            'parent_phone' => $student->parent_phone,
            'emergency_contact' => $student->emergency_contact,
            'blood_group' => $student->blood_group,
            'medical_conditions' => $student->medical_conditions,
            'previous_school' => $student->previous_school,
            'transfer_certificate' => $student->transfer_certificate,
            'user' => [
                'id' => $student->user->id,
                'name' => $student->user->name,
                'email' => $student->user->email,
                'photo' => $student->user->photo,
                'phone' => $student->user->phone,
                'address' => $student->user->address,
                'date_of_birth' => $student->user->date_of_birth,
                'gender' => $student->user->gender,
            ],
            'parentUser' => $student->parentUser ? [
                'id' => $student->parentUser->id,
                'user_id' => $student->parentUser->user_id,
                'phone' => $student->parentUser->phone,
                'address' => $student->parentUser->address,
                'user' => [
                    'id' => $student->parentUser->user->id,
                    'name' => $student->parentUser->user->name,
                    'email' => $student->parentUser->user->email,
                    'phone' => $student->parentUser->user->phone,
                ],
            ] : null,
        ];
    }

    public function getFormOptions(): array
    {
        return $this->remember('students:form:options', function () {
            return [
                'classes' => AcademicClass::query()
                    ->select(['id', 'name', 'code'])
                    ->get()
                    ->map(fn($class) => [
                        'id' => $class->id,
                        'name' => $class->name,
                        'code' => $class->code,
                    ])->toArray(),
                'sections' => Section::query()
                    ->select(['id', 'name'])
                    ->get()
                    ->map(fn($section) => [
                        'id' => $section->id,
                        'name' => $section->name,
                    ])->toArray(),
            ];
        }, $this->cacheTTL * 2); // Cache form options longer (20 minutes)
    }

    public function createStudent(array $data): Student
    {
        // Create user
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        // Handle photo upload
        if (!empty($data['photo'])) {
            $photoPath = $data['photo']->store('photos', 'public');
            $user->update(['photo' => $photoPath]);
        }

        // Generate unique roll number
        $rollNumber = $data['roll_number'] ?? 'ROLL' . date('Y') . str_pad(Student::count() + 1, 4, '0', STR_PAD_LEFT);

        $student = Student::create([
            'user_id' => $user->id,
            'academic_class_id' => $data['academic_class_id'],
            'section_id' => $data['section_id'] ?? null,
            'admission_number' => $data['admission_number'],
            'roll_number' => $rollNumber,
            'date_of_birth' => $data['date_of_birth'] ?? null,
            'gender' => !empty($data['gender']) ? ucfirst(strtolower($data['gender'])) : null,
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
            'admission_date' => $data['admission_date'] ?? null,
            'status' => !empty($data['status']) ? strtolower($data['status']) : 'active',
            'parent_name' => $data['parent_name'] ?? null,
            'parent_email' => $data['parent_email'] ?? null,
            'parent_phone' => $data['parent_phone'] ?? null,
            'emergency_contact' => $data['emergency_contact'] ?? null,
            'blood_group' => $data['blood_group'] ?? null,
            'medical_conditions' => $data['medical_conditions'] ?? null,
            'previous_school' => $data['previous_school'] ?? null,
            'transfer_certificate' => $data['transfer_certificate'] ?? null,
        ]);

        $user->assignRole('student');

        $this->clearStudentCache();

        return $student;
    }

    public function updateStudent(Student $student, array $data): Student
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

        $student->user->update($userData);

        // Update student record
        $student->update([
            'academic_class_id' => $data['academic_class_id'],
            'section_id' => $data['section_id'] ?? null,
            'admission_number' => $data['admission_number'],
            'roll_number' => $data['roll_number'],
            'date_of_birth' => $data['date_of_birth'] ?? null,
            'gender' => !empty($data['gender']) ? ucfirst(strtolower($data['gender'])) : null,
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
            'admission_date' => $data['admission_date'] ?? null,
            'status' => !empty($data['status']) ? strtolower($data['status']) : 'active',
            'parent_name' => $data['parent_name'] ?? null,
            'parent_email' => $data['parent_email'] ?? null,
            'parent_phone' => $data['parent_phone'] ?? null,
            'emergency_contact' => $data['emergency_contact'] ?? null,
            'blood_group' => $data['blood_group'] ?? null,
            'medical_conditions' => $data['medical_conditions'] ?? null,
            'previous_school' => $data['previous_school'] ?? null,
            'transfer_certificate' => $data['transfer_certificate'] ?? null,
        ]);

        $this->clearStudentCache($student->id);

        return $student->fresh();
    }

    public function deleteStudent(Student $student): void
    {
        $student->user->delete(); // Cascades to student
        $this->clearStudentCache($student->id);
    }

    public function clearStudentCache(?int $studentId = null): void
    {
        if ($studentId) {
            Cache::forget("student:{$studentId}:detail");
        }
        // Clear all list caches
        Cache::flush();
    }
}
