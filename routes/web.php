<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PhotoController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\AcademicClassController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\FeeController;
use App\Http\Controllers\TimetableController;
use App\Http\Controllers\ReportCardController;
use App\Http\Controllers\ParentController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\TransportController;
use App\Http\Controllers\NoticeController;
use App\Http\Controllers\MapController;

Route::middleware(['auth', 'verified'])->group(function () {

    // ─── Dashboard (all authenticated users) ──────────────────────
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ─── Student Search ───────────────────────────────────────────
    Route::get('/students/search', [StudentController::class, 'search'])
        ->middleware('permission:view students')
        ->name('students.search');

    Route::get('/students/all', [StudentController::class, 'getAllStudents'])
        ->middleware('permission:view students')
        ->name('students.all');

    // ─── Students ─────────────────────────────────────────────────
    // Create routes MUST come before {student} wildcard routes
    Route::middleware('permission:manage students|create students')->group(function () {
        Route::get('students/create', [StudentController::class, 'create'])->name('students.create');
        Route::post('students', [StudentController::class, 'store'])->name('students.store');
    });

    Route::middleware('permission:manage students')->group(function () {
        Route::get('students/{student}/edit', [StudentController::class, 'edit'])->name('students.edit');
        Route::put('students/{student}', [StudentController::class, 'update'])->name('students.update');
        Route::delete('students/{student}', [StudentController::class, 'destroy'])->name('students.destroy');
    });

    Route::middleware('permission:view students')->group(function () {
        Route::get('students', [StudentController::class, 'index'])->name('students.index');
        Route::get('students/{student}', [StudentController::class, 'show'])->name('students.show');
    });

    // ─── Teachers ─────────────────────────────────────────────────
    Route::middleware('permission:manage teachers')->group(function () {
        Route::get('teachers/create', [TeacherController::class, 'create'])->name('teachers.create');
        Route::post('teachers', [TeacherController::class, 'store'])->name('teachers.store');
        Route::get('teachers/{teacher}/edit', [TeacherController::class, 'edit'])->name('teachers.edit');
        Route::put('teachers/{teacher}', [TeacherController::class, 'update'])->name('teachers.update');
        Route::delete('teachers/{teacher}', [TeacherController::class, 'destroy'])->name('teachers.destroy');
    });

    Route::middleware('permission:view teachers')->group(function () {
        Route::get('teachers', [TeacherController::class, 'index'])->name('teachers.index');
        Route::get('teachers/{teacher}', [TeacherController::class, 'show'])->name('teachers.show');
    });

    // ─── Classes ──────────────────────────────────────────────────
    Route::middleware('permission:manage classes')->group(function () {
        Route::get('classes/create', [AcademicClassController::class, 'create'])->name('classes.create');
        Route::post('classes', [AcademicClassController::class, 'store'])->name('classes.store');
        Route::get('classes/{class}/edit', [AcademicClassController::class, 'edit'])->name('classes.edit');
        Route::put('classes/{class}', [AcademicClassController::class, 'update'])->name('classes.update');
        Route::delete('classes/{class}', [AcademicClassController::class, 'destroy'])->name('classes.destroy');
    });

    Route::middleware('permission:view classes')->group(function () {
        Route::get('classes', [AcademicClassController::class, 'index'])->name('classes.index');
        Route::get('classes/{class}', [AcademicClassController::class, 'show'])->name('classes.show');
    });

    // ─── Subjects ─────────────────────────────────────────────────
    Route::middleware('permission:manage subjects')->group(function () {
        Route::get('subjects/create', [SubjectController::class, 'create'])->name('subjects.create');
        Route::post('subjects', [SubjectController::class, 'store'])->name('subjects.store');
        Route::get('subjects/{subject}/edit', [SubjectController::class, 'edit'])->name('subjects.edit');
        Route::put('subjects/{subject}', [SubjectController::class, 'update'])->name('subjects.update');
        Route::delete('subjects/{subject}', [SubjectController::class, 'destroy'])->name('subjects.destroy');
    });

    Route::middleware('permission:view subjects')->group(function () {
        Route::get('subjects', [SubjectController::class, 'index'])->name('subjects.index');
        Route::get('subjects/{subject}', [SubjectController::class, 'show'])->name('subjects.show');
    });

    // ─── Fees / Account ───────────────────────────────────────────
    Route::middleware('permission:manage fees')->group(function () {
        Route::get('fees/create', [FeeController::class, 'create'])->name('fees.create');
        Route::post('fees', [FeeController::class, 'store'])->name('fees.store');
        Route::get('fees/{fee}/edit', [FeeController::class, 'edit'])->name('fees.edit');
        Route::put('fees/{fee}', [FeeController::class, 'update'])->name('fees.update');
        Route::delete('fees/{fee}', [FeeController::class, 'destroy'])->name('fees.destroy');
    });

    Route::middleware('permission:view fees')->group(function () {
        Route::get('fees', [FeeController::class, 'index'])->name('fees.index');
        Route::get('fees/{fee}', [FeeController::class, 'show'])->name('fees.show');
    });

    // ─── Exams ────────────────────────────────────────────────────
    Route::middleware('permission:manage exams')->group(function () {
        Route::get('exams/create', [ExamController::class, 'create'])->name('exams.create');
        Route::post('exams', [ExamController::class, 'store'])->name('exams.store');
        Route::get('exams/{exam}/edit', [ExamController::class, 'edit'])->name('exams.edit');
        Route::put('exams/{exam}', [ExamController::class, 'update'])->name('exams.update');
        Route::delete('exams/{exam}', [ExamController::class, 'destroy'])->name('exams.destroy');
    });

    Route::middleware('permission:view exams')->group(function () {
        Route::get('exams', [ExamController::class, 'index'])->name('exams.index');
        Route::get('exams/{exam}', [ExamController::class, 'show'])->name('exams.show');
    });

    // ─── Attendance ───────────────────────────────────────────────
    Route::middleware('permission:view attendance')->group(function () {
        Route::get('attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    });

    Route::middleware(['permission:mark attendance', 'throttle:60,1'])->group(function () {
        Route::post('attendance', [AttendanceController::class, 'store'])->name('attendance.store');
    });

    // ─── Grades ───────────────────────────────────────────────────
    Route::middleware('permission:view grades')->group(function () {
        Route::get('grades', [GradeController::class, 'index'])->name('grades.index');
    });

    Route::middleware(['permission:edit grades', 'throttle:60,1'])->group(function () {
        Route::get('exams/{exam}/grades', [GradeController::class, 'enter'])->name('exams.grades.enter');
        Route::post('exams/{exam}/grades', [GradeController::class, 'store'])->name('exams.grades.store');
    });

    // ─── Timetable / Class Routine ────────────────────────────────
    Route::middleware('permission:view class routine')->group(function () {
        Route::get('timetable', [TimetableController::class, 'index'])->name('timetable.index');
    });

    // ─── Report Cards ─────────────────────────────────────────────
    Route::middleware('permission:view report cards')->group(function () {
        Route::get('/report-cards', [ReportCardController::class, 'index'])->name('report-cards.index');
        Route::get('/report-cards/generate/{studentId}', [ReportCardController::class, 'generate'])->name('report-cards.generate');
    });

    Route::middleware('permission:manage report cards|create report cards')->group(function () {
        Route::post('/report-cards/share-whatsapp/{studentId}', [ReportCardController::class, 'shareWhatsApp'])->name('report-cards.share-whatsapp');
        Route::get('/report-cards/bulk', [ReportCardController::class, 'bulkGenerate'])->name('report-cards.bulk');
    });

    // ─── Parents ───────────────────────────────────────────────────
    Route::middleware('permission:manage parents')->group(function () {
        Route::get('parents/create', [ParentController::class, 'create'])->name('parents.create');
        Route::post('parents', [ParentController::class, 'store'])->name('parents.store');
        Route::get('parents/{parent}/edit', [ParentController::class, 'edit'])->name('parents.edit');
        Route::put('parents/{parent}', [ParentController::class, 'update'])->name('parents.update');
        Route::delete('parents/{parent}', [ParentController::class, 'destroy'])->name('parents.destroy');
    });

    Route::middleware('permission:view parents')->group(function () {
        Route::get('parents', [ParentController::class, 'index'])->name('parents.index');
        Route::get('parents/{parent}', [ParentController::class, 'show'])->name('parents.show');
    });

    // ─── Library ───────────────────────────────────────────────────
    Route::middleware('permission:view library')->group(function () {
        Route::get('library', [LibraryController::class, 'index'])->name('library.index');
        Route::get('library/{id}', [LibraryController::class, 'show'])->name('library.show');
    });

    // ─── Transport ─────────────────────────────────────────────────
    Route::middleware('permission:view transport')->group(function () {
        Route::get('transport', [TransportController::class, 'index'])->name('transport.index');
        Route::get('transport/{id}', [TransportController::class, 'show'])->name('transport.show');
    });

    // ─── Notice ───────────────────────────────────────────────────
    Route::middleware('permission:view notices')->group(function () {
        Route::get('notice', [NoticeController::class, 'index'])->name('notice.index');
        Route::get('notice/{id}', [NoticeController::class, 'show'])->name('notice.show');
    });

    // ─── Map ───────────────────────────────────────────────────────
    Route::middleware('permission:view map')->group(function () {
        Route::get('map', [MapController::class, 'index'])->name('map.index');
    });

    // ─── Profile (all authenticated users) ────────────────────────
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ─── Photo (all authenticated users) ───────────────────────────
    Route::get('/photo', [PhotoController::class, 'edit'])->name('photo.edit');
    Route::post('/photo', [PhotoController::class, 'update'])->name('photo.update');
    Route::patch('/photo', [PhotoController::class, 'update'])->name('photo.update');
    Route::delete('/photo', [PhotoController::class, 'destroy'])->name('photo.destroy');
    Route::get('/api/photo/{userId}', [PhotoController::class, 'getPhoto'])->name('photo.get');
});

require __DIR__.'/auth.php';
