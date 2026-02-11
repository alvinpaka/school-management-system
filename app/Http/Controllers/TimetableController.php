<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class TimetableController extends Controller
{
    /**
     * Display the timetable index page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        // Get the authenticated user
        $user = $request->user();
        
        // Initialize timetable data
        $timetable = [];
        $classes = [];
        
        // If user is a student, get their classes and timetable
        if ($user->hasRole('student')) {
            $student = $user->student;
            if ($student) {
                $classes = [$student->academicClass];
                // TODO: Get actual timetable data for the student
                $timetable = $this->getStudentTimetable($student);
            }
        }
        // If user is a teacher, get their assigned classes and timetable
        elseif ($user->hasRole('teacher')) {
            $teacher = $user->teacher;
            if ($teacher) {
                // TODO: Get classes assigned to this teacher
                $classes = [];
                $timetable = $this->getTeacherTimetable($teacher);
            }
        }
        // If user is admin, get all classes and timetable
        elseif ($user->hasRole('admin')) {
            // TODO: Get all classes
            $classes = [];
            $timetable = [];
        }
        
        return Inertia::render('Timetable/Index', [
            'timetable' => $timetable,
            'classes' => $classes,
        ]);
    }
    
    /**
     * Get timetable for a specific student.
     *
     * @param  \App\Models\Student  $student
     * @return array
     */
    private function getStudentTimetable($student)
    {
        // TODO: Implement actual timetable logic
        // This should return the student's weekly schedule
        return [
            [
                'day' => 'Monday',
                'periods' => [
                    [
                        'time' => '8:00 - 9:00',
                        'subject' => 'Mathematics',
                        'teacher' => 'Mr. Johnson',
                        'room' => 'Room 201'
                    ],
                    [
                        'time' => '9:30 - 10:30',
                        'subject' => 'Science',
                        'teacher' => 'Dr. Smith',
                        'room' => 'Lab 101'
                    ],
                    [
                        'time' => '11:00 - 12:00',
                        'subject' => 'English',
                        'teacher' => 'Ms. Davis',
                        'room' => 'Room 105'
                    ]
                ]
            ],
            [
                'day' => 'Tuesday',
                'periods' => [
                    [
                        'time' => '8:00 - 9:00',
                        'subject' => 'History',
                        'teacher' => 'Mr. Brown',
                        'room' => 'Room 102'
                    ],
                    [
                        'time' => '9:30 - 10:30',
                        'subject' => 'Geography',
                        'teacher' => 'Ms. Wilson',
                        'room' => 'Room 103'
                    ],
                    [
                        'time' => '11:00 - 12:00',
                        'subject' => 'Physical Education',
                        'teacher' => 'Mr. Davis',
                        'room' => 'Gym'
                    ]
                ]
            ]
        ];
    }
    
    /**
     * Get timetable for a specific teacher.
     *
     * @param  \App\Models\Teacher  $teacher
     * @return array
     */
    private function getTeacherTimetable($teacher)
    {
        // TODO: Implement actual timetable logic
        // This should return the teacher's weekly schedule
        return [];
    }
}
