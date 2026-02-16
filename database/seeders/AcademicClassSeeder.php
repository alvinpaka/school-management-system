<?php

namespace Database\Seeders;

use App\Models\AcademicClass;
use App\Models\Section;
use Illuminate\Database\Seeder;

class AcademicClassSeeder extends Seeder
{
    public function run(): void
    {
        // Create Academic Classes (S1-S6)
        $classes = [
            ['name' => 'S1', 'code' => 'S1'],
            ['name' => 'S2', 'code' => 'S2'],
            ['name' => 'S3', 'code' => 'S3'],
            ['name' => 'S4', 'code' => 'S4'],
            ['name' => 'S5', 'code' => 'S5'],
            ['name' => 'S6', 'code' => 'S6'],
        ];

        foreach ($classes as $classData) {
            $class = AcademicClass::firstOrCreate($classData);
            
            // Create Sections East, West, North, South for each class
            $sections = ['East', 'West', 'North', 'South'];
            foreach ($sections as $sectionName) {
                Section::firstOrCreate([
                    'name' => $sectionName,
                    'academic_class_id' => $class->id,
                ]);
            }
        }
    }
}
