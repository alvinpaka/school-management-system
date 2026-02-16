<?php

namespace Database\Seeders;

use App\Models\Fee;
use App\Models\Student;
use Illuminate\Database\Seeder;

class FeeSeeder extends Seeder
{
    public function run(): void
    {
        $students = Student::all();
        $feeTypes = ['Tuition Fee', 'Library Fee', 'Lab Fee', 'Sports Fee', 'Transport Fee'];
        
        foreach ($students as $student) {
            foreach ($feeTypes as $type) {
                $amount = $this->getFeeAmount($type);
                $dueDate = now()->addDays(rand(1, 60));
                $status = $this->getRandomStatus();
                
                Fee::firstOrCreate([
                    'student_id' => $student->id,
                    'fee_type' => $type,
                    'amount' => $amount,
                    'due_date' => $dueDate,
                    'status' => $status,
                    'paid_date' => $this->getPaidDate($dueDate, $status),
                    'remarks' => $type . ' for ' . $student->roll_number . ' - ' . $dueDate->format('F Y'),
                ]);
            }
        }
    }

    private function getFeeAmount($type): float
    {
        $amounts = [
            'Tuition Fee' => rand(5000, 15000),
            'Library Fee' => rand(500, 2000),
            'Lab Fee' => rand(1000, 3000),
            'Sports Fee' => rand(300, 1500),
            'Transport Fee' => rand(800, 2500),
        ];
        
        return $amounts[$type] ?? 1000;
    }

    private function getRandomStatus(): string
    {
        $statuses = ['paid', 'paid', 'paid', 'pending', 'partially_paid'];
        return $statuses[array_rand($statuses)];
    }

    private function getPaidDate($dueDate, $status): ?string
    {
        return $status === 'paid' ? $dueDate->copy()->subDays(rand(1, 30))->format('Y-m-d') : null;
    }

    private function getPaymentMethod(): ?string
    {
        $status = $this->getRandomStatus();
        
        if ($status === 'paid') {
            $methods = ['Cash', 'Bank Transfer', 'Credit Card', 'Online Payment'];
            return $methods[array_rand($methods)];
        }
        
        return null;
    }
}