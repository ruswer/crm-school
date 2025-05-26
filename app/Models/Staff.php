<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Qo'shildi
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes; // Qo'shildi

class Staff extends Model
{
    use HasFactory, SoftDeletes; // SoftDeletes qo'shildi

    protected $fillable = [
        'first_name',
        'last_name',
        'position_id',
        'status',
        'branch_id',
        'role_id',
        'department_id',
        'email',
        'phone',
        'photo',
    ];

    protected $dates = ['deleted_at']; // SoftDeletes uchun

    // --- Relationships ---

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function department(): BelongsTo
    {
        // Agar Department modeli mavjud bo'lsa
        return $this->belongsTo(Department::class);
    }

    public function position(): BelongsTo
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
        return $this->hasMany(Student::class, 'trial_teacher_id');
    }

    // Xodimning davomat yozuvlari bilan aloqadorligi (bir xodimda ko'p davomat yozuvi bo'lishi mumkin).
    public function attendanceRecords(): HasMany
    {
        return $this->hasMany(AttendanceRecord::class, 'staff_id');
    }

    // Xodimning davomat yozuvlari bilan aloqadorligi (bir xodimda ko'p davomat yozuvi bo'lishi mumkin).
    public function salaryPayments(): HasMany
    {
        return $this->hasMany(SalaryPayment::class);
    }
}
