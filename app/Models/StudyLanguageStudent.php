<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudyLanguageStudent extends Model
{
    protected $fillable = [
        'student_id',
        'language'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
