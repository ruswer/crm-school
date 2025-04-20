<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentStatus extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'color', 'is_active'];

    public function students()
    {
        return $this->hasMany(Student::class, 'status_id');
    }
}
