<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudyDayStudent extends Model
{
    protected $fillable = [
        'student_id',
        'day'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}