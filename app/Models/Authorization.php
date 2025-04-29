<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Authorization extends Model
{
    use HasFactory;

    protected $fillable = [
        'login',
        'password',
        'authenticatable_id',
        'authenticatable_type',
    ];

    public function authenticatable(): MorphTo
    {
        return $this->morphTo();
    }
}
