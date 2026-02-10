<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fee extends Model
{
    protected $fillable = [
        'student_id',
        'amount',
        'fee_type',
        'due_date',
        'paid_date',
        'status',
        'remarks',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
