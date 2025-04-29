<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RemovalReason extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active',
    ];

    // Agar studentlar bilan bog'lanish kerak bo'lsa (ixtiyoriy)
    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
