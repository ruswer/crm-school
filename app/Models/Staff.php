<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Staff extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'position',
        'status'
    ];

    public function trialStudents()
    {
        return $this->hasMany(Student::class, 'trial_teacher_id');
    }

    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn () => "{$this->first_name} {$this->last_name}",
        );
    }
}
