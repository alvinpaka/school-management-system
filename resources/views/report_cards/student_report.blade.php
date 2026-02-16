<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Student Report Card</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 10px;
            background-color: #f5f5f5;
        }
        
        .report-card {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 0;
            border: 1px solid #000;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px;
            text-align: center;
        }
        
        .school-info h1 {
            font-size: 20px;
            margin: 0 0 3px 0;
            font-weight: bold;
        }
        
        .school-info p {
            margin: 2px 0;
            font-size: 11px;
        }
        
        .academic-year {
            background: #f8f9fa;
            padding: 8px;
            text-align: center;
            border-bottom: 1px solid #dee2e6;
            font-weight: bold;
            color: #333;
            font-size: 12px;
        }
        
        .student-info {
            display: flex;
            padding: 10px;
            background: #fff;
            border-bottom: 1px solid #dee2e6;
        }
        
        .student-photo {
            width: 70px;
            height: 80px;
            border: 1px solid #dee2e6;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 9px;
            color: #6c757d;
        }
        
        .student-details {
            flex: 1;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 5px;
        }
        
        .detail-item {
            display: flex;
            align-items: center;
        }
        
        .detail-label {
            font-weight: bold;
            color: #495057;
            margin-right: 5px;
            min-width: 80px;
            font-size: 10px;
        }
        
        .detail-value {
            color: #212529;
            font-weight: 500;
            font-size: 10px;
        }
        
        .grades-section {
            padding: 10px;
        }
        
        .section-title {
            background: #343a40;
            color: white;
            padding: 6px 10px;
            margin: 0 0 8px 0;
            font-weight: bold;
            text-align: center;
            font-size: 11px;
        }
        
        .grades-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        
        .grades-table th,
        .grades-table td {
            border: 1px solid #dee2e6;
            padding: 5px;
            text-align: center;
        }
        
        .grades-table th {
            background: #6c757d;
            color: white;
            font-weight: bold;
            font-size: 9px;
        }
        
        .grades-table td {
            font-size: 9px;
        }
        
        .grades-table tr:nth-child(even) {
            background: #f8f9fa;
        }
        
        .grade-excellent {
            color: #28a745;
            font-weight: bold;
        }
        
        .grade-good {
            color: #007bff;
            font-weight: bold;
        }
        
        .grade-average {
            color: #ffc107;
            font-weight: bold;
        }
        
        .grade-poor {
            color: #dc3545;
            font-weight: bold;
        }
        
        .summary-section {
            padding: 10px;
            background: #f8f9fa;
            border-top: 1px solid #dee2e6;
        }
        
        .summary-stats-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            background: white;
        }
        
        .summary-stats-table td {
            border: 1px solid #dee2e6;
            padding: 10px;
            text-align: center;
            width: 33.33%;
        }
        
        .summary-stats-table .stats-label {
            font-weight: bold;
            color: #495057;
            font-size: 11px;
            display: block;
            margin-bottom: 5px;
        }
        
        .summary-stats-table .stats-value {
            font-size: 18px;
            font-weight: bold;
            color: #212529;
        }
        
        .remarks-section {
            margin-top: 10px;
        }
        
        .remarks-box {
            background: white;
            border: 1px solid #dee2e6;
            padding: 8px;
            min-height: 40px;
            font-size: 9px;
        }
        
        .signature-section {
            padding: 10px;
            background: white;
            border-top: 1px solid #dee2e6;
        }
        
        .signature-grid {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 20px;
            margin-top: 20px;
        }
        
        .signature-box {
            text-align: center;
            flex: 1;
        }
        
        .signature-line {
            border-bottom: 1px solid #000;
            margin-bottom: 3px;
            height: 30px;
        }
        
        .signature-title {
            font-weight: bold;
            color: #495057;
            font-size: 9px;
            white-space: nowrap;
        }
        
        .footer {
            text-align: center;
            padding: 8px;
            background: #f8f9fa;
            border-top: 1px solid #dee2e6;
            font-size: 8px;
            color: #6c757d;
        }
        
        @media print {
            body {
                background: white;
                padding: 0;
            }
            
            .report-card {
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
    <div class="report-card">
        <!-- Header -->
        <div class="header">
            <div class="school-info">
                <h1>{{ $school_info['name'] }}</h1>
                <p>{{ $school_info['address'] }}</p>
                <p>Phone: {{ $school_info['phone'] }} | Email: {{ $school_info['email'] }}</p>
            </div>
        </div>

        <!-- Academic Year -->
        <div class="academic-year">
            ACADEMIC YEAR {{ date('Y') }} - {{ date('Y', strtotime('+1 year')) }}
        </div>

        <!-- Student Information -->
        <div class="student-info">
            <div class="student-photo">
                [PHOTO]
            </div>
            <div class="student-details">
                <div class="detail-item">
                    <span class="detail-label">Student Name:</span>
                    <span class="detail-value">{{ $student->user->name }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Admission No:</span>
                    <span class="detail-value">{{ $student->admission_number }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Class:</span>
                    <span class="detail-value">{{ $student->academicClass->name ?? 'N/A' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Section:</span>
                    <span class="detail-value">{{ $student->section->name ?? 'N/A' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Date of Birth:</span>
                    <span class="detail-value">{{ $student->date_of_birth ? \Carbon\Carbon::parse($student->date_of_birth)->format('d/m/Y') : 'N/A' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Gender:</span>
                    <span class="detail-value">N/A</span>
                </div>
            </div>
        </div>

        <!-- Grades Section -->
        <div class="grades-section">
            <div class="section-title">ACADEMIC PERFORMANCE</div>
            
            <table class="grades-table">
                <thead>
                    <tr>
                        <th style="width: 40px;">S.No</th>
                        <th style="width: 150px;">Subject</th>
                        <th style="width: 80px;">Exam</th>
                        <th style="width: 60px;">Marks</th>
                        <th style="width: 60px;">Grade</th>
                        <th style="width: 120px;">Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($grades->isEmpty())
                        <tr>
                            <td colspan="6" style="text-align: center; color: #6c757d;">
                                No grades available for this student
                            </td>
                        </tr>
                    @else
                        <?php $sno = 1; ?>
                        @foreach ($grades as $examGrades)
                            @foreach ($examGrades as $grade)
                                <tr>
                                    <td>{{ $sno++ }}</td>
                                    <td>{{ is_object($grade->subject) ? $grade->subject->name : ($grade->subject ?? 'N/A') }}</td>
                                    <td>{{ is_object($grade->exam) ? $grade->exam->name : ($grade->exam ?? 'N/A') }}</td>
                                    <td>{{ $grade->marks ?? 'N/A' }}</td>
                                    <td>
                                        @if ($grade->grade >= 80)
                                            <span class="grade-excellent">{{ $grade->grade }}</span>
                                        @elseif ($grade->grade >= 60)
                                            <span class="grade-good">{{ $grade->grade }}</span>
                                        @elseif ($grade->grade >= 40)
                                            <span class="grade-average">{{ $grade->grade }}</span>
                                        @else
                                            <span class="grade-poor">{{ $grade->grade }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $grade->remarks ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Summary Section -->
        <div class="summary-section">
            <div class="section-title">SUMMARY</div>
            
            <!-- TOTAL MARKS, AVERAGE GRADE, POSITION in Table Format -->
            <table class="summary-stats-table">
                <tr>
                    <td>
                        <span class="stats-label">TOTAL MARKS</span>
                        <span class="stats-value">{{ $grades->flatten()->sum('marks') ?? 0 }}</span>
                    </td>
                    <td>
                        <span class="stats-label">AVERAGE GRADE</span>
                        <span class="stats-value">{{ $grades->flatten()->avg('grade') ? number_format($grades->flatten()->avg('grade'), 1) : 'N/A' }}</span>
                    </td>
                    <td>
                        <span class="stats-label">POSITION</span>
                        <span class="stats-value">N/A</span>
                    </td>
                </tr>
            </table>
            
            <div class="remarks-section">
                <div class="summary-label">TEACHER'S REMARKS:</div>
                <div class="remarks-box">
                    {{ $grades->flatten()->first()->remarks ?? 'Good performance. Keep up the excellent work!' }}
                </div>
            </div>
        </div>

        <!-- Signature Section -->
        <div class="signature-section">
            <div class="signature-grid">
                <div class="signature-box">
                    <div class="signature-line"></div>
                    <div class="signature-title">CLASS TEACHER</div>
                </div>
                <div class="signature-box">
                    <div class="signature-line"></div>
                    <div class="signature-title">HEAD TEACHER</div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Generated on: {{ $generated_at }}</p>
            <p>This is a computer-generated document. No signature required.</p>
        </div>
    </div>
</body>
</html>