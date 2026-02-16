<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::firstOrCreate([
            'email' => 'admin@school.edu',
        ], [
            'name' => 'System Administrator',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // Teachers
        $teachers = [
            ['John Smith', 'john.smith@school.edu', 'Mathematics'],
            ['Sarah Johnson', 'sarah.johnson@school.edu', 'English'],
            ['Michael Brown', 'michael.brown@school.edu', 'Science'],
            ['Emily Davis', 'emily.davis@school.edu', 'History'],
            ['Robert Wilson', 'robert.wilson@school.edu', 'Geography'],
            ['Lisa Anderson', 'lisa.anderson@school.edu', 'Physics'],
            ['David Martinez', 'david.martinez@school.edu', 'Chemistry'],
            ['Jennifer Taylor', 'jennifer.taylor@school.edu', 'Biology'],
        ];

        foreach ($teachers as [$name, $email, $specialization]) {
            User::firstOrCreate([
                'email' => $email,
            ], [
                'name' => $name,
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]);
        }

        // Students
        $students = [
            ['Alice Johnson', 'alice.johnson@student.edu'],
            ['Bob Smith', 'bob.smith@student.edu'],
            ['Charlie Brown', 'charlie.brown@student.edu'],
            ['Diana Prince', 'diana.prince@student.edu'],
            ['Edward Norton', 'edward.norton@student.edu'],
            ['Fiona Green', 'fiona.green@student.edu'],
            ['George Miller', 'george.miller@student.edu'],
            ['Hannah White', 'hannah.white@student.edu'],
            ['Ian Black', 'ian.black@student.edu'],
            ['Julia Roberts', 'julia.roberts@student.edu'],
            ['Kevin Hart', 'kevin.hart@student.edu'],
            ['Laura Palmer', 'laura.palmer@student.edu'],
            ['Mark Davis', 'mark.davis@student.edu'],
            ['Nancy Drew', 'nancy.drew@student.edu'],
            ['Oliver Twist', 'oliver.twist@student.edu'],
            ['Penny Wise', 'penny.wise@student.edu'],
            ['Quinn Fabray', 'quinn.fabray@student.edu'],
            ['Rachel Green', 'rachel.green@student.edu'],
            ['Sam Wilson', 'sam.wilson@student.edu'],
            ['Tina Turner', 'tina.turner@student.edu'],
            ['Uma Thurman', 'uma.thurman@student.edu'],
            ['Victor Von Doom', 'victor.von.doom@student.edu'],
            ['Wanda Maximoff', 'wanda.maximoff@student.edu'],
            ['Xavier Charles', 'xavier.charles@student.edu'],
            ['Yara Shahidi', 'yara.shahidi@student.edu'],
            ['Zoe Saldana', 'zoe.saldana@student.edu'],
        ];

        foreach ($students as [$name, $email]) {
            User::firstOrCreate([
                'email' => $email,
            ], [
                'name' => $name,
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]);
        }

        // Parents
        $parents = [
            ['James Johnson', 'james.johnson@parent.edu'],
            ['Mary Smith', 'mary.smith@parent.edu'],
            ['Robert Brown', 'robert.brown@parent.edu'],
            ['Patricia Prince', 'patricia.prince@parent.edu'],
            ['William Norton', 'william.norton@parent.edu'],
            ['Jennifer Green', 'jennifer.green@parent.edu'],
            ['Michael Miller', 'michael.miller@parent.edu'],
            ['Linda White', 'linda.white@parent.edu'],
            ['David Black', 'david.black@parent.edu'],
            ['Elizabeth Roberts', 'elizabeth.roberts@parent.edu'],
        ];

        foreach ($parents as [$name, $email]) {
            User::firstOrCreate([
                'email' => $email,
            ], [
                'name' => $name,
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]);
        }

        // Other Staff
        $staff = [
            ['Accountant', 'accountant@school.edu'],
            ['Librarian', 'librarian@school.edu'],
            ['Receptionist', 'receptionist@school.edu'],
        ];

        foreach ($staff as [$name, $email]) {
            User::firstOrCreate([
                'email' => $email,
            ], [
                'name' => $name,
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]);
        }
    }
}
