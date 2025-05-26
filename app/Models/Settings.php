<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    use HasFactory;

    protected $fillable = [
        'logo_path',
        'center_name',
        'address',
        'phone',
        'email',
        'session',
        'language',
        'daily_payment',
        'timezone',
    ];
}