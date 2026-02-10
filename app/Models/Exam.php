<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $fillable = [
        'name',
        'subject_id',
        'date',
        'start_time',
        'end_time',
        'max_marks',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
