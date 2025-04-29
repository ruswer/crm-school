<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'passport_number',
        'branch_id',
        'first_name',
        'last_name',
        'gender',
        'birth_date',
        'phone',
        'email',
        'status_id',
        'points',
        'study_language',
        'knowledge_level_id',
        'study_days',
        'marketing_source_id',
        'notes',
        'trial_teacher_id',
        'trial_called_at',
        'trial_attended_at',
        'removal_reason_id',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'points' => 'decimal:2',
        'trial_called_at' => 'datetime',
        'trial_attended_at' => 'datetime',
        'study_days' => 'array',
    ];
    protected $dates = ['deleted_at'];

    // Relationships
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function trialTeacher(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'trial_teacher_id');
    }

    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'student_courses')
            ->withTimestamps();
    }
    
    public function parents(): HasMany
    {
        return $this->hasMany(Parents::class);
    }

    public function marketingSource(): BelongsTo
    {
        return $this->belongsTo(MarketingSource::class);
    }

    public function status()
    {
        return $this->belongsTo(StudentStatus::class);
    }

    public function knowledgeLevel()
    {
        return $this->belongsTo(KnowledgeLevel::class);
    }

    public function studyLanguagesStudents()
    {
        return $this->hasMany(StudyLanguageStudent::class);
    }

    public function studyLanguages()
    {
        return $this->belongsTo(StudyLanguage::class);
    }

    public function studyDays()
    {
        return $this->hasMany(StudyDayStudent::class);
    }

    public function studyDayStudents()
    {
        return $this->hasMany(StudyDayStudent::class, 'student_id');
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'student_groups')
            ->withPivot('discount', 'debt') 
            ->withTimestamps();
    }

    public function authorization(): MorphOne 
    {
        return $this->morphOne(Authorization::class, 'authenticatable');
    }

    public function removalReason()
    {
        return $this->belongsTo(RemovalReason::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }
}