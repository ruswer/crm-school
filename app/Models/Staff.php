<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Qo'shildi
use Illuminate\Database\Eloquent\SoftDeletes; // Qo'shildi

class Staff extends Model
{
    use HasFactory, SoftDeletes; // SoftDeletes qo'shildi

    protected $fillable = [
        'first_name',
        'last_name',
        'position',
        'status',
        'branch_id',
        'role_id',
        'department_id',
        'email',
        'phone',
    ];

    protected $dates = ['deleted_at']; // SoftDeletes uchun

    // --- Relationships ---

    public function branch(): BelongsTo
    {
        // Agar Branch modeli mavjud bo'lsa
        return $this->belongsTo(Branch::class);
    }

    public function role(): BelongsTo
    {
        // Agar Role modeli mavjud bo'lsa
        return $this->belongsTo(Role::class);
    }

    public function department(): BelongsTo
    {
        // Agar Department modeli mavjud bo'lsa
        return $this->belongsTo(Department::class);
    }

    public function position(): BelongsTo // Aloqa nomini 'position' deb o'zgartirdik
    {
        return $this->belongsTo(Position::class);
    }

    // --- Accessors ---

    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn () => trim("{$this->first_name} {$this->last_name}"),
        );
    }

    // --- Eski Relationships (Agar kerak bo'lsa) ---
    public function trialStudents()
    {
        // Agar Student modeli mavjud bo'lsa
        return $this->hasMany(Student::class, 'trial_teacher_id');
    }
}
