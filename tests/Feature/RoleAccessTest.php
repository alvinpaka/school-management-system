<?php

namespace Tests\Feature;

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\StudentController;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPermissionSeeder::class);
    }

    // ─── ADMIN ────────────────────────────────────────────────────

    public function test_admin_can_access_students()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->get(route('students.index'));
        $response->assertStatus(200);
    }

    public function test_admin_can_access_teachers()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->get(route('teachers.index'));
        $response->assertStatus(200);
    }

    public function test_admin_can_access_fees()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->get(route('fees.index'));
        $response->assertStatus(200);
    }

    public function test_admin_can_access_exams()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->get(route('exams.index'));
        $response->assertStatus(200);
    }

    public function test_admin_can_access_attendance()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->get(route('attendance.index'));
        $response->assertStatus(200);
    }

    public function test_admin_can_access_report_cards()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->get(route('report-cards.index'));
        $response->assertStatus(200);
    }

    // ─── TEACHER ──────────────────────────────────────────────────

    public function test_teacher_can_access_students_view()
    {
        $teacher = User::factory()->create();
        $teacher->assignRole('teacher');

        $response = $this->actingAs($teacher)->get(route('students.index'));
        $response->assertStatus(200);
    }

    public function test_teacher_can_access_attendance()
    {
        $teacher = User::factory()->create();
        $teacher->assignRole('teacher');

        $response = $this->actingAs($teacher)->get(route('attendance.index'));
        $response->assertStatus(200);
    }

    public function test_teacher_can_access_grades()
    {
        $teacher = User::factory()->create();
        $teacher->assignRole('teacher');

        $response = $this->actingAs($teacher)->get(route('grades.index'));
        $response->assertStatus(200);
    }

    public function test_teacher_cannot_manage_students()
    {
        $teacher = User::factory()->create();
        $teacher->assignRole('teacher');

        $response = $this->actingAs($teacher)->get(route('students.create'));
        $response->assertStatus(403);
    }

    public function test_teacher_cannot_access_fees()
    {
        $teacher = User::factory()->create();
        $teacher->assignRole('teacher');

        $response = $this->actingAs($teacher)->get(route('fees.index'));
        $response->assertStatus(403);
    }

    // ─── STUDENT ──────────────────────────────────────────────────

    public function test_student_can_access_dashboard()
    {
        $student = User::factory()->create();
        $student->assignRole('student');

        $response = $this->actingAs($student)->get(route('dashboard'));
        $response->assertStatus(200);
    }

    public function test_student_can_view_exams()
    {
        $student = User::factory()->create();
        $student->assignRole('student');

        $response = $this->actingAs($student)->get(route('exams.index'));
        $response->assertStatus(200);
    }

    public function test_student_can_view_fees()
    {
        $student = User::factory()->create();
        $student->assignRole('student');

        $response = $this->actingAs($student)->get(route('fees.index'));
        $response->assertStatus(200);
    }

    public function test_student_cannot_manage_students()
    {
        $student = User::factory()->create();
        $student->assignRole('student');

        $response = $this->actingAs($student)->get(route('students.create'));
        $response->assertStatus(403);
    }

    public function test_student_cannot_mark_attendance()
    {
        $student = User::factory()->create();
        $student->assignRole('student');

        $response = $this->actingAs($student)->post(route('attendance.store'), []);
        $response->assertStatus(403);
    }

    public function test_student_cannot_manage_exams()
    {
        $student = User::factory()->create();
        $student->assignRole('student');

        $response = $this->actingAs($student)->get(route('exams.create'));
        $response->assertStatus(403);
    }

    // ─── PARENT ───────────────────────────────────────────────────

    public function test_parent_can_access_dashboard()
    {
        $parent = User::factory()->create();
        $parent->assignRole('parent');

        $response = $this->actingAs($parent)->get(route('dashboard'));
        $response->assertStatus(200);
    }

    public function test_parent_can_view_fees()
    {
        $parent = User::factory()->create();
        $parent->assignRole('parent');

        $response = $this->actingAs($parent)->get(route('fees.index'));
        $response->assertStatus(200);
    }

    public function test_parent_can_view_attendance()
    {
        $parent = User::factory()->create();
        $parent->assignRole('parent');

        $response = $this->actingAs($parent)->get(route('attendance.index'));
        $response->assertStatus(200);
    }

    public function test_parent_cannot_manage_students()
    {
        $parent = User::factory()->create();
        $parent->assignRole('parent');

        $response = $this->actingAs($parent)->get(route('students.create'));
        $response->assertStatus(403);
    }

    public function test_parent_cannot_access_manage_fees()
    {
        $parent = User::factory()->create();
        $parent->assignRole('parent');

        $response = $this->actingAs($parent)->get(route('fees.create'));
        $response->assertStatus(403);
    }

    // ─── ACCOUNTANT ───────────────────────────────────────────────

    public function test_accountant_can_access_dashboard()
    {
        $accountant = User::factory()->create();
        $accountant->assignRole('accountant');

        $response = $this->actingAs($accountant)->get(route('dashboard'));
        $response->assertStatus(200);
    }

    public function test_accountant_can_manage_fees()
    {
        $accountant = User::factory()->create();
        $accountant->assignRole('accountant');

        $response = $this->actingAs($accountant)->get(route('fees.index'));
        $response->assertStatus(200);

        $response = $this->actingAs($accountant)->get(route('fees.create'));
        $response->assertStatus(200);
    }

    public function test_accountant_can_view_students()
    {
        $accountant = User::factory()->create();
        $accountant->assignRole('accountant');

        $response = $this->actingAs($accountant)->get(route('students.index'));
        $response->assertStatus(200);
    }

    public function test_accountant_cannot_manage_exams()
    {
        $accountant = User::factory()->create();
        $accountant->assignRole('accountant');

        $response = $this->actingAs($accountant)->get(route('exams.index'));
        $response->assertStatus(403);
    }

    public function test_accountant_cannot_manage_attendance()
    {
        $accountant = User::factory()->create();
        $accountant->assignRole('accountant');

        $response = $this->actingAs($accountant)->get(route('attendance.index'));
        $response->assertStatus(403);
    }

    // ─── LIBRARIAN ────────────────────────────────────────────────

    public function test_librarian_can_access_dashboard()
    {
        $librarian = User::factory()->create();
        $librarian->assignRole('librarian');

        $response = $this->actingAs($librarian)->get(route('dashboard'));
        $response->assertStatus(200);
    }

    public function test_librarian_can_view_students()
    {
        $librarian = User::factory()->create();
        $librarian->assignRole('librarian');

        $response = $this->actingAs($librarian)->get(route('students.index'));
        $response->assertStatus(200);
    }

    public function test_librarian_cannot_access_fees()
    {
        $librarian = User::factory()->create();
        $librarian->assignRole('librarian');

        $response = $this->actingAs($librarian)->get(route('fees.index'));
        $response->assertStatus(403);
    }

    public function test_librarian_cannot_access_exams()
    {
        $librarian = User::factory()->create();
        $librarian->assignRole('librarian');

        $response = $this->actingAs($librarian)->get(route('exams.index'));
        $response->assertStatus(403);
    }

    public function test_librarian_cannot_manage_students()
    {
        $librarian = User::factory()->create();
        $librarian->assignRole('librarian');

        $response = $this->actingAs($librarian)->get(route('students.create'));
        $response->assertStatus(403);
    }

    // ─── RECEPTIONIST ─────────────────────────────────────────────

    public function test_receptionist_can_access_dashboard()
    {
        $receptionist = User::factory()->create();
        $receptionist->assignRole('receptionist');

        $response = $this->actingAs($receptionist)->get(route('dashboard'));
        $response->assertStatus(200);
    }

    public function test_receptionist_can_register_students()
    {
        $receptionist = User::factory()->create();
        $receptionist->assignRole('receptionist');

        $response = $this->actingAs($receptionist)->get(route('students.create'));
        $response->assertStatus(200);
    }

    public function test_receptionist_can_view_students()
    {
        $receptionist = User::factory()->create();
        $receptionist->assignRole('receptionist');

        $response = $this->actingAs($receptionist)->get(route('students.index'));
        $response->assertStatus(200);
    }

    public function test_receptionist_cannot_manage_exams()
    {
        $receptionist = User::factory()->create();
        $receptionist->assignRole('receptionist');

        $response = $this->actingAs($receptionist)->get(route('exams.create'));
        $response->assertStatus(403);
    }

    public function test_receptionist_cannot_access_fees()
    {
        $receptionist = User::factory()->create();
        $receptionist->assignRole('receptionist');

        $response = $this->actingAs($receptionist)->get(route('fees.index'));
        $response->assertStatus(403);
    }

    public function test_receptionist_cannot_access_exams()
    {
        $receptionist = User::factory()->create();
        $receptionist->assignRole('receptionist');

        $response = $this->actingAs($receptionist)->get(route('exams.index'));
        $response->assertStatus(403);
    }

    // ─── UNAUTHENTICATED ──────────────────────────────────────────

    public function test_unauthenticated_user_cannot_access_dashboard()
    {
        $response = $this->get(route('dashboard'));
        $response->assertRedirect(route('login'));
    }
}
