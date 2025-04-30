<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Position extends Model
{
    use HasFactory;

    protected $fillable = ['name']; // 'name' maydonini ommaviy to'ldirishga ruxsat berish

    public function staff(): HasMany
    {
        return $this->hasMany(Staff::class);
    }
}
