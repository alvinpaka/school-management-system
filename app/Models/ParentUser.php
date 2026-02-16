<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParentUser extends Model
{
    protected $fillable = [
        'user_id',
        'phone',
        'address',
        'occupation',
        'relationship_to_student',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'parent_user_id');
    }
}
