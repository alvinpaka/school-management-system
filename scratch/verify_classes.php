<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\AcademicClass;

$classes = AcademicClass::withCount(['students as total_students'])
    ->with(['classTeacher.user', 'sections'])
    ->get();

foreach ($classes as $class) {
    echo "Class: {$class->name} ({$class->code})\n";
    echo "  Level: {$class->grade_level}\n";
    echo "  Students: {$class->total_students}\n";
    echo "  Lead Teacher: " . ($class->classTeacher?->user?->name ?? 'Unassigned') . "\n";
    echo "  Sections: " . $class->sections->pluck('name')->implode(', ') . "\n";
    echo "-------------------\n";
}
