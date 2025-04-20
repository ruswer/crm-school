<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudyLanguage extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'is_active'];

    public function students()
    {
        return $this->hasMany(Student::class, 'study_language_id');
    }
}
