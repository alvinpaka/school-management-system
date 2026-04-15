<?php

namespace App\Services;

use App\Models\AcademicClass;
use App\Models\Section;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class AcademicClassService extends BaseService
{
    protected int $cacheTTL = 600;

    public function getClassesList(?string $search = null, ?array $classIds = null, int $perPage = 10): LengthAwarePaginator
    {
        $cacheKey = $this->getCacheKey('classes:list:v6', [$search, $classIds, $perPage, request('page', 1)]);

        return $this->remember($cacheKey, function () use ($search, $classIds, $perPage) {
            $query = AcademicClass::query()
                ->withCount(['students as total_students'])
                ->with(['sections:id,academic_class_id,name', 'teachers.user']);

            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('code', 'like', "%{$search}%")
                      ->orWhereHas('sections', function($subQuery) use ($search) {
                          $subQuery->where('name', 'like', "%{$search}%");
                      });
                });
            }

            if ($classIds && !empty($classIds)) {
                $query->whereIn('id', $classIds);
            }

            // Robust natural sort: Extract number from "S1", "S2", etc.
            return $query->orderByRaw('CAST(SUBSTRING(name, 2) AS UNSIGNED) ASC')
                ->orderBy('name', 'asc')
                ->paginate($perPage);
        }, $this->cacheTTL);
    }

    public function getClassesForUser(string $role, ?array $classIds = null, ?int $studentClassId = null): Collection
    {
        $cacheKey = $this->getCacheKey('classes:user', [$role, $classIds, $studentClassId]);

        return $this->remember($cacheKey, function () use ($role, $classIds, $studentClassId) {
            $query = AcademicClass::query()
                ->select(['id', 'name', 'code'])
                ->with(['sections:id,academic_class_id,name']);

            if ($role === 'student' && $studentClassId) {
                return $query->where('id', $studentClassId)->get();
            }

            if ($role === 'parent' && $classIds) {
                $query->whereIn('id', $classIds);
            }

            return $query->get();
        }, $this->cacheTTL);
    }

    public function getClassDetail(AcademicClass $class): array
    {
        return $this->remember("class:{$class->id}:detail:v3", function () use ($class) {
            $class->load([
                'sections' => function($query) {
                    $query->withCount('students')->with(['teachers.user']);
                }
            ]);

            return [
                'id' => $class->id,
                'name' => $class->name,
                'code' => $class->code,
                'grade_level' => $class->grade_level,
                'total_population' => $class->sections->sum('students_count'),
                'created_at' => $class->created_at,
                'updated_at' => $class->updated_at,
                'sections' => $class->sections->map(fn($section) => [
                    'id' => $section->id,
                    'name' => $section->name,
                    'students_count' => $section->students_count,
                    'class_teacher' => $section->classTeacher ? [
                        'name' => $section->classTeacher->user->name,
                        'phone' => $section->classTeacher->phone ?? null,
                    ] : null,
                ])->toArray(),
            ];
        }, $this->cacheTTL);
    }

    public function createClass(array $data): AcademicClass
    {
        $class = AcademicClass::create([
            'name' => $data['name'],
            'code' => $data['code'],
        ]);

        if (!empty($data['sections'])) {
            foreach ($data['sections'] as $sectionName) {
                Section::create([
                    'academic_class_id' => $class->id,
                    'name' => $sectionName,
                ]);
            }
        }

        $this->clearClassCache();

        return $class;
    }

    public function updateClass(AcademicClass $class, array $data): AcademicClass
    {
        $class->update([
            'name' => $data['name'],
            'code' => $data['code'],
        ]);

        $class->sections()->delete();

        if (!empty($data['sections'])) {
            foreach ($data['sections'] as $sectionName) {
                Section::create([
                    'academic_class_id' => $class->id,
                    'name' => $sectionName,
                ]);
            }
        }

        $this->clearClassCache($class->id);

        return $class->fresh();
    }

    public function deleteClass(AcademicClass $class): void
    {
        $class->delete();
        $this->clearClassCache($class->id);
    }

    public function clearClassCache(?int $classId = null): void
    {
        if ($classId) {
            \Cache::forget("class:{$classId}:detail");
        }
        \Cache::flush();
    }
}
