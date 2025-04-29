<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute; // Attribute cast uchun

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'group_id',
        'name',
        'type',
        'exam_date',
        'exam_time',
        'duration',
        'location',
        'description',
        'status',
        'max_score',
        'passing_score',
        'results_published_at',
        'created_by',
    ];

    // Sanalarni avtomatik Carbon obyektiga o'girish uchun
    protected $casts = [
        'exam_date' => 'date',
        'results_published_at' => 'datetime',
        'max_score' => 'decimal:2', // Agar decimal ishlatilsa
        'passing_score' => 'decimal:2', // Agar decimal ishlatilsa
    ];

    // --- Relationships ---

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function creator(): BelongsTo // created_by uchun
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
