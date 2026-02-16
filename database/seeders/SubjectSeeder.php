<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $oLevelSubjects = [
            ['name' => 'English', 'code' => 'ENG-OL', 'type' => 'theory', 'level' => 'O-Level'],
            ['name' => 'Mathematics', 'code' => 'MATH-OL', 'type' => 'theory', 'level' => 'O-Level'],
            ['name' => 'Biology', 'code' => 'BIO-OL', 'type' => 'both', 'level' => 'O-Level'],
            ['name' => 'Chemistry', 'code' => 'CHEM-OL', 'type' => 'both', 'level' => 'O-Level'],
            ['name' => 'Physics', 'code' => 'PHY-OL', 'type' => 'both', 'level' => 'O-Level'],
            ['name' => 'History & Political Education', 'code' => 'HIST-OL', 'type' => 'theory', 'level' => 'O-Level'],
            ['name' => 'Geography', 'code' => 'GEOG-OL', 'type' => 'theory', 'level' => 'O-Level'],
            ['name' => 'Kiswahili', 'code' => 'KIS-OL', 'type' => 'theory', 'level' => 'O-Level'],
            ['name' => 'French', 'code' => 'FRE-OL', 'type' => 'theory', 'level' => 'O-Level'],
            ['name' => 'German', 'code' => 'GER-OL', 'type' => 'theory', 'level' => 'O-Level'],
            ['name' => 'Arabic', 'code' => 'ARA-OL', 'type' => 'theory', 'level' => 'O-Level'],
            ['name' => 'Literature in English', 'code' => 'LIT-OL', 'type' => 'theory', 'level' => 'O-Level'],
            ['name' => 'Fine Art', 'code' => 'ART-OL', 'type' => 'practical', 'level' => 'O-Level'],
            ['name' => 'Performing Arts', 'code' => 'PA-OL', 'type' => 'practical', 'level' => 'O-Level'],
            ['name' => 'Computer Studies (ICT)', 'code' => 'ICT-OL', 'type' => 'both', 'level' => 'O-Level'],
            ['name' => 'Agriculture', 'code' => 'AGR-OL', 'type' => 'both', 'level' => 'O-Level'],
            ['name' => 'Entrepreneurship', 'code' => 'ENT-OL', 'type' => 'theory', 'level' => 'O-Level'],
            ['name' => 'Nutrition & Food Technology', 'code' => 'NFT-OL', 'type' => 'practical', 'level' => 'O-Level'],
        ];

        $aLevelSubjects = [
            ['name' => 'Biology', 'code' => 'BIO-AL', 'type' => 'both', 'level' => 'A-Level'],
            ['name' => 'Chemistry', 'code' => 'CHEM-AL', 'type' => 'both', 'level' => 'A-Level'],
            ['name' => 'Physics', 'code' => 'PHY-AL', 'type' => 'both', 'level' => 'A-Level'],
            ['name' => 'Mathematics', 'code' => 'MATH-AL', 'type' => 'theory', 'level' => 'A-Level'],
            ['name' => 'Economics', 'code' => 'ECON-AL', 'type' => 'theory', 'level' => 'A-Level'],
            ['name' => 'History', 'code' => 'HIST-AL', 'type' => 'theory', 'level' => 'A-Level'],
            ['name' => 'Geography', 'code' => 'GEOG-AL', 'type' => 'theory', 'level' => 'A-Level'],
            ['name' => 'Literature in English', 'code' => 'LIT-AL', 'type' => 'theory', 'level' => 'A-Level'],
            ['name' => 'Entrepreneurship', 'code' => 'ENT-AL', 'type' => 'theory', 'level' => 'A-Level'],
            ['name' => 'Subsidiary Maths', 'code' => 'SUB-MATH', 'type' => 'theory', 'level' => 'A-Level'],
            ['name' => 'Subsidiary ICT', 'code' => 'SUB-ICT', 'type' => 'practical', 'level' => 'A-Level'],
            ['name' => 'General Paper', 'code' => 'GP', 'type' => 'theory', 'level' => 'A-Level'],
        ];

        $allSubjects = array_merge($oLevelSubjects, $aLevelSubjects);

        foreach ($allSubjects as $subject) {
            Subject::firstOrCreate($subject);
        }
    }
}
