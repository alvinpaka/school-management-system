<?php

namespace App\Http\Controllers;

use App\Models\AcademicClass;
use App\Models\Section;
use App\Services\DashboardService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    protected DashboardService $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            return Inertia::render('Dashboard/Admin', [
                'stats' => $this->dashboardService->getAdminStats(),
                'recentActivities' => $this->dashboardService->getRecentActivities(5),
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
            ]);
        }

        // Check specific roles first (more restrictive) before general roles
        if ($user->hasRole('accountant')) {
            return Inertia::render('Dashboard/Accountant', [
                'stats' => $this->dashboardService->getAccountantStats(),
            ]);
        }

        if ($user->hasRole('librarian')) {
            return Inertia::render('Dashboard/Librarian', [
                'stats' => $this->dashboardService->getLibrarianStats(),
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'photo' => $user->photo,
                ],
            ]);
        }

        if ($user->hasRole('receptionist')) {
            return Inertia::render('Dashboard/Receptionist', [
                'stats' => $this->dashboardService->getReceptionistStats(),
            ]);
        }

        if ($user->hasRole('teacher')) {
            return Inertia::render('Dashboard/Teacher', [
                'teacher' => $this->dashboardService->getTeacherData($user),
            ]);
        }

        if ($user->hasRole('student')) {
            return Inertia::render('Dashboard/Student', [
                'student' => $this->dashboardService->getStudentData($user),
            ]);
        }

        if ($user->hasRole('parent')) {
            $parentData = $this->dashboardService->getParentData($user);
            return Inertia::render('Dashboard/Parent', $parentData);
        }

        // If user has no recognized role
        abort(403, 'You do not have a role assigned. Please contact the administrator.');
    }
}
