<?php

namespace App\Http\Controllers;

use App\Models\AcademicClass;
use App\Models\Exam;
use App\Models\Fee;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            // Get recent activities with timestamps for proper sorting
            $recentActivitiesData = [];
            
            // Recent student registrations
            $recentStudents = Student::with('user')
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
            
            // Recent fee payments
            $recentFees = Fee::with('student.user')
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
            
            // Recent exams
            $recentExams = Exam::with('subject')
                ->latest()
                ->take(2)
                ->get();
                
            foreach ($recentExams as $exam) {
                $examDate = $exam->exam_date ? Carbon::parse($exam->exam_date) : null;
                $recentActivitiesData[] = [
                    'title' => 'Exam Scheduled',
                    'description' => $exam->title . ' scheduled for ' . ($examDate ? $examDate->format('M j, Y') : 'upcoming'),
                    'time' => $exam->created_at->diffForHumans(),
                    'timestamp' => $exam->created_at->timestamp,
                    'icon' => 'Calendar',
                    'color' => 'text-purple-500'
                ];
            }
            
            // Sort by timestamp (most recent first) and take only latest 5
            usort($recentActivitiesData, function($a, $b) {
                return $b['timestamp'] - $a['timestamp'];
            });
            $recentActivities = array_slice($recentActivitiesData, 0, 5);

            return Inertia::render('Dashboard/Admin', [
                'stats' => [
                    'students_count' => Student::count(),
                    'teachers_count' => Teacher::count(),
                    'classes_count' => AcademicClass::count(),
                ],
                'recentActivities' => $recentActivities
            ]);
        }

        // Check specific roles first (more restrictive) before general roles
        if ($user->hasRole('accountant')) {
            return Inertia::render('Dashboard/Accountant', [
                'stats' => [
                    'total_fees' => Fee::sum('amount'),
                    'paid_fees' => Fee::where('status', 'paid')->sum('amount'),
                    'pending_fees' => Fee::where('status', 'pending')->sum('amount'),
                    'students_count' => Student::count(),
                ],
            ]);
        }

        if ($user->hasRole('librarian')) {
            return Inertia::render('Dashboard/Librarian', [
                'user' => $user,
            ]);
        }

        if ($user->hasRole('receptionist')) {
            return Inertia::render('Dashboard/Receptionist', [
                'stats' => [
                    'students_count' => Student::count(),
                    'teachers_count' => Teacher::count(),
                ],
            ]);
        }

        if ($user->hasRole('teacher')) {
            return Inertia::render('Dashboard/Teacher', [
                'teacher' => $user->teacher ? $user->teacher->load(['user', 'classes']) : null,
            ]);
        }

        if ($user->hasRole('student')) {
            return Inertia::render('Dashboard/Student', [
                'student' => $user->student ? $user->student->load(['user', 'academicClass', 'section']) : null,
            ]);
        }

        if ($user->hasRole('parent')) {
            $parentUser = $user->parentUser?->load(['user']);
            $children = $parentUser ? $parentUser->students->load(['user', 'academicClass', 'section']) : collect();
            
            return Inertia::render('Dashboard/Parent', [
                'parent' => $parentUser,
                'children' => $children,
            ]);
        }

        // If user has no recognized role
        abort(403, 'You do not have a role assigned. Please contact the administrator.');
    }
}
