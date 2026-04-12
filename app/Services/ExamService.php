<?php

namespace App\Services;

use App\Models\Exam;
use App\Models\Subject;
use Illuminate\Pagination\LengthAwarePaginator;

class ExamService extends BaseService
{
    protected int $cacheTTL = 600;

    public function getExamsList(?string $search = null, int $perPage = 10): LengthAwarePaginator
    {
        $cacheKey = $this->getCacheKey('exams:list', [$search, $perPage, request('page', 1)]);

        return $this->remember($cacheKey, function () use ($search, $perPage) {
            $query = Exam::query()
                ->select(['id', 'subject_id', 'name', 'date', 'start_time', 'end_time', 'max_marks', 'created_at'])
                ->with(['subject:id,name,code']);

            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('date', 'like', "%{$search}%")
                      ->orWhereHas('subject', function($subQuery) use ($search) {
                          $subQuery->where('name', 'like', "%{$search}%");
                      });
                });
            }

            return $query->latest()->paginate($perPage);
        }, $this->cacheTTL);
    }

    public function getExamDetail(Exam $exam): array
    {
        return $this->remember("exam:{$exam->id}:detail", function () use ($exam) {
            $exam->load(['subject:id,name,code']);

            return [
                'id' => $exam->id,
                'subject_id' => $exam->subject_id,
                'name' => $exam->name,
                'date' => $exam->date,
                'start_time' => $exam->start_time,
                'end_time' => $exam->end_time,
                'max_marks' => $exam->max_marks,
                'created_at' => $exam->created_at,
                'updated_at' => $exam->updated_at,
                'subject' => [
                    'id' => $exam->subject->id,
                    'name' => $exam->subject->name,
                    'code' => $exam->subject->code,
                ],
            ];
        }, $this->cacheTTL);
    }

    public function getSubjectsForSelect(): array
    {
        return $this->remember('exams:subjects:select', function () {
            return Subject::query()
                ->select(['id', 'name', 'code'])
                ->get()
                ->map(fn($subject) => [
                    'id' => $subject->id,
                    'name' => $subject->name,
                    'code' => $subject->code,
                ])->toArray();
        }, $this->cacheTTL * 2);
    }

    public function createExam(array $data): Exam
    {
        $exam = Exam::create($data);
        $this->clearExamCache();
        return $exam;
    }

    public function updateExam(Exam $exam, array $data): Exam
    {
        $exam->update($data);
        $this->clearExamCache($exam->id);
        return $exam->fresh();
    }

    public function deleteExam(Exam $exam): void
    {
        $exam->delete();
        $this->clearExamCache($exam->id);
    }

    public function clearExamCache(?int $examId = null): void
    {
        if ($examId) {
            \Cache::forget("exam:{$examId}:detail");
        }
        \Cache::flush();
    }
}
