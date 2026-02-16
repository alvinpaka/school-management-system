<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class MapController extends Controller
{
    public function index()
    {
        return Inertia::render('Map/Index', [
            'schoolInfo' => [
                'name' => 'Springfield Educational Complex',
                'address' => '123 Education Street, Learning City, LC 12345',
                'phone' => '+1-800-EDU-1234',
                'email' => 'info@springfield.edu',
                'website' => 'www.springfield.edu',
                'coordinates' => [
                    'lat' => 40.7128,
                    'lng' => -74.0060,
                ],
            ],
            'facilities' => [
                [
                    'name' => 'Main Campus',
                    'type' => 'academic',
                    'description' => 'Main school building with classrooms, offices, and cafeteria',
                    'coordinates' => [
                        'lat' => 40.7128,
                        'lng' => -74.0060,
                    ],
                ],
                [
                    'name' => 'Sports Complex',
                    'type' => 'sports',
                    'description' => 'Sports fields, gymnasium, swimming pool, and tennis courts',
                    'coordinates' => [
                        'lat' => 40.7130,
                        'lng' => -74.0055,
                    ],
                ],
                [
                    'name' => 'Library',
                    'type' => 'library',
                    'description' => 'Main library building with study areas and computer labs',
                    'coordinates' => [
                        'lat' => 40.7125,
                        'lng' => -74.0065,
                    ],
                ],
                [
                    'name' => 'Science Labs',
                    'type' => 'laboratory',
                    'description' => 'Science laboratories with modern equipment',
                    'coordinates' => [
                        'lat' => 40.7132,
                        'lng' => -74.0070,
                    ],
                ],
            ],
            'transportRoutes' => [
                [
                    'id' => 1,
                    'name' => 'Route A - North Campus',
                    'stops' => [
                        ['name' => 'Main Gate', 'time' => '07:00 AM', 'students' => 15],
                        ['name' => 'North Residential Area', 'time' => '07:05 AM', 'students' => 8],
                        ['name' => 'Downtown Station', 'time' => '07:10 AM', 'students' => 12],
                        ['name' => 'School Campus', 'time' => '07:15 AM', 'students' => 25],
                        ['name' => 'Library', 'time' => '07:20 AM', 'students' => 5],
                    ],
                ],
                [
                    'id' => 2,
                    'name' => 'Route B - South Campus',
                    'stops' => [
                        ['name' => 'South Gate', 'time' => '07:00 AM', 'students' => 18],
                        ['name' => 'South Residential Area', 'time' => '07:05 AM', 'students' => 10],
                        ['name' => 'Shopping Center', 'time' => '07:10 AM', 'students' => 8],
                        ['name' => 'Community Center', 'time' => '07:15 AM', 'students' => 7],
                    ],
                ],
            ],
            'emergencyServices' => [
                [
                    'name' => 'Hospital',
                    'address' => '456 Health Avenue, Medical District',
                    'phone' => '911',
                    'distance' => '2.5 miles',
                ],
                [
                    'name' => 'Police Station',
                    'address' => '789 Safety Boulevard, Security District',
                    'phone' => '555-123-4567',
                    'distance' => '1.8 miles',
                ],
                [
                    'name' => 'Fire Department',
                    'address' => '321 Emergency Lane, Fire District',
                    'phone' => '555-987-6543',
                    'distance' => '2.2 miles',
                ],
            ],
        ]);
    }
}
