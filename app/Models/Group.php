<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = [
        'name',
        'status'
    ];

    public function students()
    {
        return $this->belongsToMany(Student::class, 'students_groups')
            ->withTimestamps();    
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
