<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cabinet extends Model
{
    protected $fillable = [
        'name',
        'branch_id',
        'description',
        // ...other fillable fields
    ];

    /**
     * Get the schedules for the cabinet.
     */
    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    // ...other relationships and methods
}