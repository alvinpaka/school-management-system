<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class TransportController extends Controller
{
    public function index()
    {
        return Inertia::render('Transport/Index', [
            'routes' => [
                [
                    'id' => 1,
                    'name' => 'Route A - North Campus',
                    'description' => 'Covers North residential areas and downtown',
                    'vehicle_type' => 'Bus',
                    'capacity' => 45,
                    'departure_time' => '07:00 AM',
                    'arrival_time' => '04:30 PM',
                    'driver' => 'John Smith',
                    'phone' => '+1234567890',
                    'status' => 'active',
                    'students_assigned' => 38,
                ],
                [
                    'id' => 2,
                    'name' => 'Route B - South Campus',
                    'description' => 'Covers South residential areas and suburbs',
                    'vehicle_type' => 'Van',
                    'capacity' => 15,
                    'departure_time' => '07:15 AM',
                    'arrival_time' => '04:45 PM',
                    'driver' => 'Jane Doe',
                    'phone' => '+0987654321',
                    'status' => 'active',
                    'students_assigned' => 22,
                ],
                [
                    'id' => 3,
                    'name' => 'Route C - East Campus',
                    'description' => 'Covers East campus and surrounding areas',
                    'vehicle_type' => 'Bus',
                    'capacity' => 50,
                    'departure_time' => '07:30 AM',
                    'arrival_time' => '05:00 PM',
                    'driver' => 'Robert Johnson',
                    'phone' => '+1122334455',
                    'status' => 'maintenance',
                    'students_assigned' => 0,
                ],
            ],
            'stats' => [
                'total_routes' => 3,
                'active_routes' => 2,
                'total_students' => 60,
                'total_vehicles' => 5,
                'average_capacity' => 36.7,
            ]
        ]);
    }

    public function show($id)
    {
        // Mock route details
        $route = [
            'id' => $id,
            'name' => 'Route A - North Campus',
            'description' => 'Covers North residential areas and downtown',
            'vehicle_type' => 'Bus',
            'vehicle_number' => 'BUS-001',
            'capacity' => 45,
            'departure_time' => '07:00 AM',
            'arrival_time' => '04:30 PM',
            'driver' => [
                'name' => 'John Smith',
                'phone' => '+1234567890',
                'license' => 'DL123456',
                'experience' => '5 years',
            ],
            'stops' => [
                ['name' => 'Main Gate', 'time' => '07:00 AM'],
                ['name' => 'North Residential Area', 'time' => '07:05 AM'],
                ['name' => 'Downtown Station', 'time' => '07:15 AM'],
                ['name' => 'School Campus', 'time' => '07:25 AM'],
                ['name' => 'Library', 'time' => '04:15 PM'],
                ['name' => 'Shopping Center', 'time' => '04:20 PM'],
                ['name' => 'Main Gate', 'time' => '04:30 PM'],
                ['name' => 'North Residential Area', 'time' => '04:35 PM'],
                ['name' => 'Downtown Station', 'time' => '04:40 PM'],
                ['name' => 'School Campus', 'time' => '04:50 PM'],
            ],
            'students_assigned' => [
                [
                    'name' => 'Student A',
                    'class' => 'Grade 10A',
                    'pickup_point' => 'North Residential Area',
                    'pickup_time' => '06:50 AM',
                ],
                [
                    'name' => 'Student B',
                    'class' => 'Grade 8B',
                    'pickup_point' => 'Downtown Station',
                    'pickup_time' => '07:10 AM',
                ],
            ],
            'status' => 'active',
        ];

        return Inertia::render('Transport/Show', [
            'route' => $route
        ]);
    }
}
