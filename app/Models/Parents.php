<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Parents extends Model
{
   
    protected $fillable = [
        'student_id',
        'full_name',
        'phone',
        'email',
    ];

    public function setPhoneAttribute($value)
    {
        $this->attributes['phone'] = preg_replace('/[^0-9]/', '', $value);
    }

    public function getPhoneAttribute($value)
    {
        return '+' . substr($value, 0, 3) . ' ' . 
               substr($value, 3, 2) . ' ' . 
               substr($value, 5, 3) . ' ' . 
               substr($value, 8, 2) . ' ' . 
               substr($value, 10);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function authorization(): MorphOne // Buni qo'shing
    {
        return $this->morphOne(Authorization::class, 'authenticatable');
    }
    
}