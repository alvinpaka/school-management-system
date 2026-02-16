<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class NoticeController extends Controller
{
    public function index()
    {
        return Inertia::render('Notice/Index', [
            'notices' => [
                [
                    'id' => 1,
                    'title' => 'School Holiday Schedule',
                    'content' => 'The school will be closed for the following holidays: Christmas (Dec 25-31), New Year (Jan 1), Easter (Mar 29-Apr 1). Please plan accordingly.',
                    'type' => 'holiday',
                    'priority' => 'high',
                    'target_audience' => 'all',
                    'published_at' => '2024-11-15',
                    'expires_at' => '2024-12-31',
                    'created_at' => '2024-11-15T10:00:00.000000Z',
                    'updated_at' => '2024-11-15T10:00:00.000000Z',
                ],
                [
                    'id' => 2,
                    'title' => 'Parent-Teacher Meeting Schedule',
                    'content' => 'Regular parent-teacher meetings will be held on the first Friday of each month. Please check the schedule for your child\'s class.',
                    'type' => 'meeting',
                    'priority' => 'medium',
                    'target_audience' => 'parents',
                    'published_at' => '2024-11-10',
                    'expires_at' => '2024-12-31',
                    'created_at' => '2024-11-10T09:00:00.000000Z',
                    'updated_at' => '2024-11-10T09:00:00.000000Z',
                ],
                [
                    'id' => 3,
                    'title' => 'Exam Schedule Update',
                    'content' => 'Final examinations will be held from Dec 15-20. Please ensure students are well-prepared. Timetable will be posted soon.',
                    'type' => 'exam',
                    'priority' => 'high',
                    'target_audience' => 'students',
                    'published_at' => '2024-11-05',
                    'expires_at' => '2024-12-20',
                    'created_at' => '2024-11-05T14:30:00.000000Z',
                    'updated_at' => '2024-11-05T14:30:00.000000Z',
                ],
                [
                    'id' => 4,
                    'title' => 'New School Policy Update',
                    'content' => 'Updated attendance and grading policies have been implemented. Please review the parent handbook for detailed information.',
                    'type' => 'policy',
                    'priority' => 'low',
                    'target_audience' => 'all',
                    'published_at' => '2024-11-01',
                    'expires_at' => '2024-12-31',
                    'created_at' => '2024-11-01T08:00:00.000000Z',
                    'updated_at' => '2024-11-01T08:00:00.000000Z',
                ],
                [
                    'id' => 5,
                    'title' => 'Sports Day Announcement',
                    'content' => 'Annual sports day will be held on Nov 15. Events include track and field competitions, tug-of-war, and relay races. Parents are welcome to attend.',
                    'type' => 'event',
                    'priority' => 'medium',
                    'target_audience' => 'all',
                    'published_at' => '2024-10-20',
                    'expires_at' => '2024-11-15',
                    'created_at' => '2024-10-20T11:00:00.000000Z',
                    'updated_at' => '2024-10-20T11:00:00.000000Z',
                ],
            ]
        ]);
    }

    public function show($id)
    {
        // Mock notice details
        $notice = [
            'id' => 1,
            'title' => 'School Holiday Schedule',
            'content' => 'The school will be closed for the following holidays: Christmas (Dec 25-31), New Year (Jan 1), Easter (Mar 29-Apr 1). Please plan accordingly.',
            'type' => 'holiday',
            'priority' => 'high',
            'target_audience' => 'all',
            'published_at' => '2024-11-15',
            'expires_at' => '2024-12-31',
            'created_at' => '2024-11-15T10:00:00.000000Z',
            'updated_at' => '2024-11-15T10:00:00.000000Z',
        ];

        return Inertia::render('Notice/Show', [
            'notice' => $notice
        ]);
    }
}
