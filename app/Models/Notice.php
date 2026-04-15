<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'type',
        'priority',
        'target_audience',
        'published_at',
        'expires_at',
        'created_by',
    ];

    protected $casts = [
        'published_at' => 'date',
        'expires_at' => 'date',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
