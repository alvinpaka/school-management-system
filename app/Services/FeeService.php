<?php

namespace App\Services;

use App\Models\Fee;
use App\Models\Student;
use Illuminate\Pagination\LengthAwarePaginator;

class FeeService extends BaseService
{
    protected int $cacheTTL = 600;

    public function getFeesList(?string $search = null, ?array $studentIds = null, int $perPage = 10): LengthAwarePaginator
    {
        $cacheKey = $this->getCacheKey('fees:list', [$search, $studentIds, $perPage, request('page', 1)]);

        return $this->remember($cacheKey, function () use ($search, $studentIds, $perPage) {
            $query = Fee::query()
                ->select(['id', 'student_id', 'fee_type', 'amount', 'status', 'due_date', 'paid_date', 'created_at'])
                ->with(['student:id,user_id,admission_number', 'student.user:id,name,email']);

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

            if ($studentIds && !empty($studentIds)) {
                $query->whereIn('student_id', $studentIds);
            }

            return $query->latest()->paginate($perPage);
        }, $this->cacheTTL);
    }

    public function getFeeDetail(Fee $fee): array
    {
        return $this->remember("fee:{$fee->id}:detail", function () use ($fee) {
            $fee->load([
                'student:id,user_id,admission_number',
                'student.user:id,name,email,phone,photo',
                'student.academicClass:id,name,code'
            ]);

            return [
                'id' => $fee->id,
                'student_id' => $fee->student_id,
                'fee_type' => $fee->fee_type,
                'amount' => $fee->amount,
                'status' => $fee->status,
                'due_date' => $fee->due_date,
                'paid_date' => $fee->paid_date,
                'created_at' => $fee->created_at,
                'updated_at' => $fee->updated_at,
                'student' => [
                    'id' => $fee->student->id,
                    'admission_number' => $fee->student->admission_number,
                    'user' => [
                        'id' => $fee->student->user->id,
                        'name' => $fee->student->user->name,
                        'email' => $fee->student->user->email,
                        'phone' => $fee->student->user->phone,
                        'photo' => $fee->student->user->photo,
                    ],
                    'academicClass' => $fee->student->academicClass ? [
                        'id' => $fee->student->academicClass->id,
                        'name' => $fee->student->academicClass->name,
                        'code' => $fee->student->academicClass->code,
                    ] : null,
                ],
            ];
        }, $this->cacheTTL);
    }

    public function getFeeForEdit(Fee $fee): array
    {
        $fee->load(['student:id,user_id,admission_number', 'student.user:id,name,email']);

        return [
            'id' => $fee->id,
            'student_id' => $fee->student_id,
            'fee_type' => $fee->fee_type,
            'amount' => $fee->amount,
            'status' => $fee->status,
            'due_date' => $fee->due_date,
            'paid_date' => $fee->paid_date,
            'student' => [
                'id' => $fee->student->id,
                'admission_number' => $fee->student->admission_number,
                'user' => [
                    'id' => $fee->student->user->id,
                    'name' => $fee->student->user->name,
                    'email' => $fee->student->user->email,
                ],
            ],
        ];
    }

    public function getStudentsForSelect(): array
    {
        return $this->remember('fees:students:select', function () {
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
                ])->toArray();
        }, $this->cacheTTL * 2);
    }

    public function createFee(array $data): Fee
    {
        $fee = Fee::create($data);
        $this->clearFeeCache();
        return $fee;
    }

    public function updateFee(Fee $fee, array $data): Fee
    {
        $fee->update($data);
        $this->clearFeeCache($fee->id);
        return $fee->fresh();
    }

    public function deleteFee(Fee $fee): void
    {
        $fee->delete();
        $this->clearFeeCache($fee->id);
    }

    public function clearFeeCache(?int $feeId = null): void
    {
        if ($feeId) {
            \Cache::forget("fee:{$feeId}:detail");
        }
        \Cache::flush();
    }
}
