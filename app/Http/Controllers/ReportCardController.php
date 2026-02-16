<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\AcademicClass;
use App\Models\Exam;
use App\Models\Grade;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ReportCardController extends Controller
{
    public function index()
    {
        $query = AcademicClass::with(['students.user']);
        
        // Filter for parents - only show classes of their children and only their children
        if (auth()->user()->hasRole('parent')) {
            $parentStudentIds = view()->shared('parentStudentIds', []);
            
            if (!empty($parentStudentIds)) {
                $query->whereHas('students', function ($q) use ($parentStudentIds) {
                    $q->whereIn('id', $parentStudentIds);
                })->with(['students' => function ($q) use ($parentStudentIds) {
                    $q->whereIn('id', $parentStudentIds);
                }]);
            }
        }
        
        $classes = $query->get();
        
        return Inertia::render('ReportCards/Index', [
            'classes' => $classes
        ]);
    }

    public function generate(Request $request, $studentId)
    {
        // Check if parent is trying to access their child's report card
        if (auth()->user()->hasRole('parent')) {
            $parentStudentIds = view()->shared('parentStudentIds', []);
            if (!empty($parentStudentIds) && !in_array($studentId, $parentStudentIds)) {
                abort(403, 'You are not authorized to view this student\'s report card.');
            }
        }
        
        $student = Student::with(['user', 'academicClass', 'section', 'grades.exam'])
            ->findOrFail($studentId);

        $grades = Grade::where('student_id', $studentId)
            ->with('exam')
            ->get()
            ->groupBy('exam_id');

        $data = [
            'student' => $student,
            'grades' => $grades,
            'school_info' => [
                'name' => 'School Management System',
                'address' => '123 School Street, City',
                'phone' => '+256 123 456 789',
                'email' => 'info@school.com'
            ],
            'generated_at' => now()->format('d/m/Y H:i')
        ];

        $pdf = Pdf::loadView('report_cards.student_report', $data)
            ->setPaper('a4')
            ->setOption('margin-top', 20)
            ->setOption('margin-bottom', 20);

        $filename = 'report_card_' . $student->admission_number . '_' . now()->format('Y-m-d_H-i-s') . '.pdf';
        
        // Save to storage
        Storage::put('report-cards/' . $filename, $pdf->output());

        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"'
        ]);
    }

    public function shareWhatsApp(Request $request, $studentId)
    {
        // Check if parent is trying to access their child's report card
        if (auth()->user()->hasRole('parent')) {
            $parentStudentIds = view()->shared('parentStudentIds', []);
            if (!empty($parentStudentIds) && !in_array($studentId, $parentStudentIds)) {
                abort(403, 'You are not authorized to share this student\'s report card.');
            }
        }
        
        $student = Student::findOrFail($studentId);
        
        // Generate the PDF first
        $pdfResponse = $this->generate($request, $studentId);
        
        // Save PDF temporarily
        $filename = 'report_card_' . $student->admission_number . '_' . now()->format('Y-m-d_H-i-s') . '.pdf';
        $filepath = storage_path('app/report-cards/' . $filename);
        
        // Create directory if it doesn't exist
        if (!is_dir(dirname($filepath))) {
            mkdir(dirname($filepath), 0755, true);
        }
        
        file_put_contents($filepath, $pdfResponse->getContent());
        
        // Generate WhatsApp share link
        $phoneNumber = $request->input('phone_number');
        if ($phoneNumber) {
            // Remove any non-digit characters
            $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);
            
            // Create WhatsApp share link
            $message = "Hello! Here is the report card for " . $student->user->name . " (Admission No: " . $student->admission_number . "). You can download it from the school portal or request a copy from the school administration.";
            $whatsappUrl = "https://wa.me/" . $phoneNumber . "?text=" . urlencode($message);
            
            return redirect()->away($whatsappUrl);
        }
        
        return back()->with('error', 'Phone number is required for WhatsApp sharing');
    }

    public function bulkGenerate(Request $request)
    {
        $classId = $request->input('class_id');
        $examId = $request->input('exam_id');
        
        $query = Student::where('academic_class_id', $classId)
            ->with(['user', 'academicClass', 'section', 'grades.exam']);
        
        // Filter for parents - only show their children
        if (auth()->user()->hasRole('parent')) {
            $parentStudentIds = view()->shared('parentStudentIds', []);
            if (!empty($parentStudentIds)) {
                $query->whereIn('id', $parentStudentIds);
            }
        }
        
        $students = $query->get();

        $reports = [];
        
        foreach ($students as $student) {
            $grades = Grade::where('student_id', $student->id)
                ->where('exam_id', $examId)
                ->with('exam')
                ->get();

            $reports[] = [
                'student' => $student,
                'grades' => $grades,
                'school_info' => [
                    'name' => 'School Management System',
                    'address' => '123 School Street, City',
                    'phone' => '+256 123 456 789',
                    'email' => 'info@school.com'
                ],
                'generated_at' => now()->format('d/m/Y H:i')
            ];
        }

        return Inertia::render('ReportCards/Bulk', [
            'reports' => $reports,
            'class' => AcademicClass::find($classId),
            'exam' => Exam::find($examId)
        ]);
    }
}
