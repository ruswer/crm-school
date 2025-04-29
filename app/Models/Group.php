<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\SoftDeletes; // SoftDeletes uchun

class Group extends Model
{
    use HasFactory, SoftDeletes; // SoftDeletes traitini qo'shing

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'branch_id', // Qo'shildi
        'status',
        'course_id', // Qo'shildi
        'knowledge_level_id', // Qo'shildi
        'teacher_id', // Qo'shildi
        'teacher_salary_type', // Qo'shildi
        'teacher_salary_amount', // Qo'shildi
        'price_period_months', // Qo'shildi (eski 'price' o'rniga)
        'lesson_days', // Qo'shildi
        'total_price', // Qo'shildi
        'lessons_count', // Qo'shildi
        'lesson_start_time', // Qo'shildi
        'lesson_end_time', // Qo'shildi
        // 'cabinet_id', // Agar kerak bo'lsa
    ];

    protected $casts = [
        // 'price' => 'decimal:2', // Bu endi 'total_price' bo'ldi
        'total_price' => 'decimal:2',
        'teacher_salary_amount' => 'decimal:2',
        'lesson_days' => 'array', // Agar JSON formatida saqlansa
        'lesson_start_time' => 'datetime:H:i', // Vaqtni formatlash uchun
        'lesson_end_time' => 'datetime:H:i',   // Vaqtni formatlash uchun
    ];

    protected $dates = ['deleted_at']; // SoftDeletes uchun

    protected function getTeachersProperty(): Collection
    {
        return Staff::query()
            // first_name va last_name NULL emasligini tekshirish
            ->whereNotNull('first_name')
            ->whereNotNull('last_name')
            // Agar kerak bo'lsa, bo'sh qatorlarni ham tekshirish
            ->where('first_name', '!=', '')
            ->where('last_name', '!=', '')
            ->get() // Filtrlangan yozuvlarni olish
            ->pluck('full_name', 'id'); // Endi pluck xavfsiz bo'lishi kerak
    }

    // --- Relationships ---

    /**
     * Get the branch that owns the group.
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Get the course that owns the group.
     */
    public function course(): BelongsTo // <-- Bu bog'lanish kerak edi
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the knowledge level associated with the group.
     */
    public function knowledgeLevel(): BelongsTo // <-- Bu bog'lanish ham kerak
    {
        return $this->belongsTo(KnowledgeLevel::class);
    }

    /**
     * Get the teacher associated with the group.
     */
    public function teacher(): BelongsTo // <-- To'g'rilandi
    {
        // Staff modeliga bog'lash
        return $this->belongsTo(Staff::class, 'teacher_id'); // <-- Group::class o'rniga Staff::class
    }

    /**
     * Get the students for the group.
     */
    public function students(): BelongsToMany
    {
        // Pivot jadval nomini tekshiring (masalan, 'group_student')
        return $this->belongsToMany(Student::class, 'student_groups') // Pivot jadval nomini to'g'rilang
            ->withPivot('discount', 'debt')
            ->withTimestamps();
    }
}
