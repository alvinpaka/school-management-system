<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class LibraryController extends Controller
{
    public function index()
    {
        return Inertia::render('Library/Index', [
            'stats' => [
                'total_books' => 1250,
                'available_books' => 1180,
                'borrowed_books' => 70,
                'overdue_books' => 5,
                'new_arrivals' => 25,
            ],
            'recentBooks' => [
                [
                    'id' => 1,
                    'title' => 'Mathematics for Beginners',
                    'author' => 'John Smith',
                    'isbn' => '978-1234567890',
                    'category' => 'Education',
                    'status' => 'available',
                    'cover_image' => null,
                ],
                [
                    'id' => 2,
                    'title' => 'Science Explorers',
                    'author' => 'Jane Doe',
                    'isbn' => '978-0987654321',
                    'category' => 'Science',
                    'status' => 'borrowed',
                    'cover_image' => null,
                    'borrowed_by' => 'Student A',
                    'due_date' => '2024-02-15',
                ],
                [
                    'id' => 3,
                    'title' => 'History of Art',
                    'author' => 'Robert Johnson',
                    'isbn' => '978-1111111111',
                    'category' => 'Arts',
                    'status' => 'available',
                    'cover_image' => null,
                ],
            ]
        ]);
    }

    public function show($id)
    {
        // Mock book details
        $book = [
            'id' => $id,
            'title' => 'Mathematics for Beginners',
            'author' => 'John Smith',
            'isbn' => '978-1234567890',
            'publisher' => 'Educational Press',
            'publication_date' => '2023-01-15',
            'pages' => 250,
            'category' => 'Education',
            'language' => 'English',
            'description' => 'A comprehensive introduction to mathematics for beginners, covering basic arithmetic, algebra, and geometry concepts.',
            'status' => 'available',
            'location' => 'Shelf A, Row 3',
            'cover_image' => null,
        ];

        return Inertia::render('Library/Show', [
            'book' => $book
        ]);
    }
}
