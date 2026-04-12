<?php

namespace App\Services;

use App\Models\ParentUser;
use App\Models\Student;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

class ParentService extends BaseService
{
    protected int $cacheTTL = 600;

    public function getParentsList(?string $search = null, int $perPage = 10): LengthAwarePaginator
    {
        $cacheKey = $this->getCacheKey('parents:list', [$search, $perPage, request('page', 1)]);

        return $this->remember($cacheKey, function () use ($search, $perPage) {
            $query = ParentUser::query()
                ->select(['id', 'user_id', 'phone', 'address', 'occupation', 'relationship_to_student'])
                ->with(['user:id,name,email,photo']);

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

            return $query->latest()->paginate($perPage);
        }, $this->cacheTTL);
    }

    public function getParentDetail(ParentUser $parent): array
    {
        return $this->remember("parent:{$parent->id}:detail", function () use ($parent) {
            $parent->load([
                'user:id,name,email,photo,phone,address',
                'students:id,user_id,academic_class_id,section_id,admission_number,roll_number',
                'students.user:id,name,email,photo',
                'students.academicClass:id,name,code',
                'students.section:id,name'
            ]);

            return [
                'id' => $parent->id,
                'user_id' => $parent->user_id,
                'phone' => $parent->phone,
                'address' => $parent->address,
                'occupation' => $parent->occupation,
                'relationship_to_student' => $parent->relationship_to_student,
                'created_at' => $parent->created_at,
                'updated_at' => $parent->updated_at,
                'user' => [
                    'id' => $parent->user->id,
                    'name' => $parent->user->name,
                    'email' => $parent->user->email,
                    'photo' => $parent->user->photo,
                    'phone' => $parent->user->phone,
                    'address' => $parent->user->address,
                ],
                'students' => $parent->students->map(fn($student) => [
                    'id' => $student->id,
                    'user_id' => $student->user_id,
                    'admission_number' => $student->admission_number,
                    'roll_number' => $student->roll_number,
                    'user' => [
                        'id' => $student->user->id,
                        'name' => $student->user->name,
                        'email' => $student->user->email,
                        'photo' => $student->user->photo,
                    ],
                    'academicClass' => [
                        'id' => $student->academicClass->id,
                        'name' => $student->academicClass->name,
                        'code' => $student->academicClass->code,
                    ],
                    'section' => $student->section ? [
                        'id' => $student->section->id,
                        'name' => $student->section->name,
                    ] : null,
                ])->toArray(),
            ];
        }, $this->cacheTTL);
    }

    public function createParent(array $data): ParentUser
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        if (!empty($data['photo'])) {
            $photoPath = $data['photo']->store('photos', 'public');
            $user->update(['photo' => $photoPath]);
        }

        $user->assignRole('parent');

        $parentUser = ParentUser::create([
            'user_id' => $user->id,
            'phone' => $data['phone'],
            'address' => $data['address'],
            'occupation' => $data['occupation'] ?? null,
            'relationship_to_student' => $data['relationship_to_student'],
        ]);

        if (!empty($data['student_ids'])) {
            Student::whereIn('id', $data['student_ids'])->update(['parent_user_id' => $parentUser->id]);
        }

        $this->clearParentCache();

        return $parentUser;
    }

    public function updateParent(ParentUser $parent, array $data): ParentUser
    {
        $userData = ['name' => $data['name'], 'email' => $data['email']];

        if (!empty($data['password'])) {
            $userData['password'] = Hash::make($data['password']);
        }

        if (!empty($data['photo'])) {
            $userData['photo'] = $data['photo']->store('photos', 'public');
        }

        $parent->user->update($userData);

        $parent->update([
            'phone' => $data['phone'],
            'address' => $data['address'],
            'occupation' => $data['occupation'] ?? null,
            'relationship_to_student' => $data['relationship_to_student'],
        ]);

        // Sync student relationships
        Student::where('parent_user_id', $parent->id)->update(['parent_user_id' => null]);
        if (!empty($data['student_ids'])) {
            Student::whereIn('id', $data['student_ids'])->update(['parent_user_id' => $parent->id]);
        }

        $this->clearParentCache($parent->id);

        return $parent->fresh();
    }

    public function deleteParent(ParentUser $parent): void
    {
        $parent->user->delete();
        $this->clearParentCache($parent->id);
    }

    public function clearParentCache(?int $parentId = null): void
    {
        if ($parentId) {
            \Cache::forget("parent:{$parentId}:detail");
        }
        \Cache::flush();
    }
}
