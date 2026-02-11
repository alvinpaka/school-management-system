<?php

namespace App\Http\Controllers;

use App\Models\AcademicClass;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            return Inertia::render('Dashboard/Admin', [
                'stats' => [
                    'students_count' => Student::count(),
                    'teachers_count' => Teacher::count(),
                    'classes_count' => AcademicClass::count(),
                ]
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

        // If user has no role, show an error page
        abort(403, 'You do not have a role assigned. Please contact the administrator.');
    }
}
