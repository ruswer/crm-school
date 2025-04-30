<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; // Staff bilan aloqa uchun

class Department extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', // Bo'lim nomi uchun
        // Agar boshqa maydonlar kerak bo'lsa, shu yerga qo'shing
    ];

    /**
     * Get the staff members associated with the department.
     *
     * Bu bo'limga tegishli xodimlarni olish uchun aloqa.
     */
    public function staff(): HasMany
    {
        return $this->hasMany(Staff::class);
    }
}
