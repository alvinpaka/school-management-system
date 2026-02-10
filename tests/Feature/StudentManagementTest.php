<?php

namespace Tests\Feature;

use App\Models\AcademicClass;
use App\Models\Section;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPermissionSeeder::class);
    }

    public function test_admin_can_create_student()
    {
        $admin = User::find(1) ?? User::factory()->create();
        if (!$admin->hasRole('admin')) $admin->assignRole('admin');

        $class = AcademicClass::firstOrCreate(['code' => 'G10'], ['name' => 'Grade 10']);
        $section = Section::firstOrCreate(['name' => 'A', 'academic_class_id' => $class->id]);

        $response = $this->actingAs($admin)->post(route('students.store'), [
            'name' => 'New Student',
            'email' => 'newstudent@example.com',
            'password' => 'password',
            'admission_number' => 'STD101',
            'academic_class_id' => $class->id,
            'section_id' => $section->id,
            'date_of_birth' => '2010-01-01',
        ]);

        $response->assertRedirect(route('students.index'));
        $this->assertDatabaseHas('users', ['email' => 'newstudent@example.com']);
        $this->assertDatabaseHas('students', ['admission_number' => 'STD101']);
    }
}
