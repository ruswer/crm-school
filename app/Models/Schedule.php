<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'group_id',
        'teacher_id',
        'cabinet_id',
        'day_of_week',
        'start_time',
        'end_time',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'day_of_week' => 'integer', // Raqam sifatida ishlatish uchun
        // Agar vaqtni Carbon obyekti sifatida ishlatmoqchi bo'lsangiz:
        // 'start_time' => 'datetime:H:i',
        // 'end_time' => 'datetime:H:i',
    ];

    /**
     * Get the branch that owns the schedule.
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class); // Branch modelini import qilishni unutmang
    }

    /**
     * Get the group that owns the schedule.
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class); // Group modelini import qilishni unutmang
    }

    /**
     * Get the teacher (user) that owns the schedule.
     */
    public function teacher()
    {
        return $this->belongsTo(Staff::class, 'teacher_id');
    }

    /**
     * Get the cabinet that owns the schedule.
     */
    public function cabinet(): BelongsTo
    {
        return $this->belongsTo(Cabinet::class); // Cabinet modelini import qilishni unutmang
    }

    /**
     * Hafta kunini matn ko'rinishida olish (ixtiyoriy)
     */
    public function getDayOfWeekTextAttribute(): string
    {
        return match ($this->day_of_week) {
            1 => 'Dushanba',
            2 => 'Seshanba',
            3 => 'Chorshanba',
            4 => 'Payshanba',
            5 => 'Juma',
            6 => 'Shanba',
            7 => 'Yakshanba',
            default => 'Noma\'lum',
        };
    }
}
