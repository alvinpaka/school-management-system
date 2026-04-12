<?php

namespace App\Services;

use App\Models\AcademicClass;
use App\Models\Exam;
use App\Models\Fee;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class DashboardService extends BaseService
{
    protected int $cacheTTL = 300; // 5 minutes for dashboard data

    public function getAdminStats(): array
    {
        return $this->remember('admin:stats', function () {
            return [
                'students_count' => Student::count(),
                'teachers_count' => Teacher::count(),
                'classes_count' => AcademicClass::count(),
            ];
        }, $this->cacheTTL);
    }

    public function getRecentActivities(int $limit = 5): array
    {
        return $this->remember("admin:activities:{$limit}", function () use ($limit) {
            $recentActivitiesData = [];

            // Recent student registrations - select only needed fields
            $recentStudents = Student::query()
                ->select(['id', 'user_id', 'created_at'])
                ->with(['user:id,name'])
                ->latest()
                ->take(3)
                ->get();

            foreach ($recentStudents as $student) {
                $recentActivitiesData[] = [
                    'title' => 'New Student Registration',
                    'description' => $student->user->name . ' has been admitted',
                    'time' => $student->created_at->diffForHumans(),
                    'timestamp' => $student->created_at->timestamp,
                    'icon' => 'Users',
                    'color' => 'text-blue-500'
                ];
            }

            // Recent fee payments - optimized query
            $recentFees = Fee::query()
                ->select(['id', 'student_id', 'amount', 'status', 'paid_date', 'updated_at'])
                ->with(['student:id,user_id', 'student.user:id,name'])
                ->where('status', 'paid')
                ->whereNotNull('paid_date')
                ->latest('paid_date')
                ->take(3)
                ->get();

            foreach ($recentFees as $fee) {
                $paidDate = $fee->paid_date ? Carbon::parse($fee->paid_date) : null;
                $timestamp = $paidDate ? $paidDate->timestamp : $fee->updated_at->timestamp;
                $recentActivitiesData[] = [
                    'title' => 'Fee Payment',
                    'description' => 'UGX ' . number_format($fee->amount) . ' received from ' . $fee->student->user->name,
                    'time' => $paidDate ? $paidDate->diffForHumans() : $fee->updated_at->diffForHumans(),
                    'timestamp' => $timestamp,
                    'icon' => 'DollarSign',
                    'color' => 'text-green-500'
                ];
            }

            // Recent exams - optimized query
            $recentExams = Exam::query()
                ->select(['id', 'name', 'date', 'created_at'])
                ->latest()
                ->take(2)
                ->get();

            foreach ($recentExams as $exam) {
                $examDate = $exam->date ? Carbon::parse($exam->date) : null;
                $recentActivitiesData[] = [
                    'title' => 'Exam Scheduled',
                    'description' => $exam->name . ' scheduled for ' . ($examDate ? $examDate->format('M j, Y') : 'upcoming'),
                    'time' => $exam->created_at->diffForHumans(),
                    'timestamp' => $exam->created_at->timestamp,
                    'icon' => 'Calendar',
                    'color' => 'text-purple-500'
                ];
            }

            // Sort by timestamp (most recent first) and take only latest
            usort($recentActivitiesData, function($a, $b) {
                return $b['timestamp'] - $a['timestamp'];
            });

            return array_slice($recentActivitiesData, 0, $limit);
        }, $this->cacheTTL);
    }

    public function getAccountantStats(): array
    {
        return $this->remember('accountant:stats', function () {
            return [
                'total_fees' => Fee::sum('amount'),
                'paid_fees' => Fee::where('status', 'paid')->sum('amount'),
                'pending_fees' => Fee::where('status', 'pending')->sum('amount'),
                'students_count' => Cache::remember('students:count', 600, fn() => Student::count()),
            ];
        }, $this->cacheTTL);
    }

    public function getReceptionistStats(): array
    {
        return $this->remember('receptionist:stats', function () {
            return [
                'students_count' => Cache::remember('students:count', 600, fn() => Student::count()),
                'teachers_count' => Cache::remember('teachers:count', 600, fn() => Teacher::count()),
            ];
        }, $this->cacheTTL);
    }

    public function getLibrarianStats(): array
    {
        return $this->remember('librarian:stats', function () {
            return [
                'students_count' => Cache::remember('students:count', 600, fn() => Student::count()),
                'teachers_count' => Cache::remember('teachers:count', 600, fn() => Teacher::count()),
                'classes_count' => Cache::remember('classes:count', 600, fn() => AcademicClass::count()),
            ];
        }, $this->cacheTTL);
    }

    public function getTeacherData(User $user): ?array
    {
        if (!$user->teacher) {
            return null;
        }

        return $this->remember("teacher:{$user->teacher->id}:data", function () use ($user) {
            $teacher = $user->teacher->load(['user:id,name,email,photo', 'classes:id,name,code']);

            return [
                'id' => $teacher->id,
                'user_id' => $teacher->user_id,
                'employee_id' => $teacher->employee_id,
                'specialization' => $teacher->specialization,
                'user' => [
                    'id' => $teacher->user->id,
                    'name' => $teacher->user->name,
                    'email' => $teacher->user->email,
                    'photo' => $teacher->user->photo,
                ],
                'classes' => $teacher->classes->map(fn($class) => [
                    'id' => $class->id,
                    'name' => $class->name,
                    'code' => $class->code,
                ])->toArray(),
            ];
        }, $this->cacheTTL);
    }

    public function getStudentData(User $user): ?array
    {
        if (!$user->student) {
            return null;
        }

        return $this->remember("student:{$user->student->id}:data", function () use ($user) {
            $student = $user->student->load([
                'user:id,name,email,photo',
                'academicClass:id,name,code',
                'section:id,name'
            ]);

            return [
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
            ];
        }, $this->cacheTTL);
    }

    public function getParentData(User $user): array
    {
        return $this->remember("parent:{$user->id}:data", function () use ($user) {
            $parentUser = $user->parentUser?->load(['user:id,name,email,phone']);

            if (!$parentUser) {
                return ['parent' => null, 'children' => []];
            }

            $children = $parentUser->students()
                ->select(['id', 'user_id', 'academic_class_id', 'section_id', 'admission_number', 'roll_number'])
                ->with([
                    'user:id,name,email,photo',
                    'academicClass:id,name,code',
                    'section:id,name'
                ])
                ->get()
                ->map(fn($student) => [
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
                ])->toArray();

            return [
                'parent' => [
                    'id' => $parentUser->id,
                    'user_id' => $parentUser->user_id,
                    'phone' => $parentUser->phone,
                    'user' => [
                        'id' => $parentUser->user->id,
                        'name' => $parentUser->user->name,
                        'email' => $parentUser->user->email,
                    ],
                ],
                'children' => $children,
            ];
        }, $this->cacheTTL);
    }

    public function clearDashboardCache(): void
    {
        Cache::forget('admin:stats');
        Cache::forget('accountant:stats');
        Cache::forget('librarian:stats');
        Cache::forget('receptionist:stats');
        Cache::forget('students:count');
        Cache::forget('teachers:count');
        Cache::forget('classes:count');
        // Clear activity caches
        for ($i = 1; $i <= 10; $i++) {
            Cache::forget("admin:activities:{$i}");
        }
    }
}
