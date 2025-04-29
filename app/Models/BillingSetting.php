<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillingSetting extends Model
{
    use HasFactory;

    /**
     * Mass assignment uchun ruxsat etilgan atributlar.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'points_discount_rate',
    ];

    /**
     * Ma'lumot turlarini belgilash (ixtiyoriy, lekin tavsiya etiladi).
     *
     * @var array<string, string>
     */
    protected $casts = [
        'points_discount_rate' => 'decimal:2', // 2 kasr aniqligi bilan decimal
    ];
}
