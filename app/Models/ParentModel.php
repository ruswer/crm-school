<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentModel extends Model
{
    use HasFactory;

    protected $table = 'parents'; // Jadval nomi

    protected $fillable = ['name', 'phone', 'email', 'student_id'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
