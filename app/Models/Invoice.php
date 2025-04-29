<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    use HasFactory;

    // Statuslar uchun konstantalar (ixtiyoriy, lekin qulay)
    public const STATUS_PENDING = 'pending';
    public const STATUS_PAID = 'paid';
    public const STATUS_PARTIAL = 'partial';
    public const STATUS_OVERDUE = 'overdue';
    public const STATUS_CANCELLED = 'cancelled'; // Bekor qilingan invoyslar uchun

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'student_id',
        'group_id',
        'period_description',
        'amount_due',
        'amount_paid',
        'due_date',
        'status',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'due_date' => 'date', // due_date ni Date obyektiga o'giradi
        'amount_due' => 'decimal:2', // Ma'lumotlar bazasidan olinganda to'g'ri formatlash
        'amount_paid' => 'decimal:2',
    ];

    /**
     * Get the student that owns the invoice.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the group associated with the invoice (if any).
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * Hisoblangan qoldiq summani olish (Accessor)
     */
    public function getRemainingAmountAttribute(): float
    {
        // amount_due va amount_paid ni float ga o'girib hisoblash
        $due = (float) $this->amount_due;
        $paid = (float) $this->amount_paid;
        $remaining = $due - $paid;
        // Juda kichik manfiy qiymatlarni 0 ga tenglash (floating point xatoliklari uchun)
        return $remaining > -0.00001 ? $remaining : 0;
    }

     /**
     * Invoys to'liq to'langanligini tekshirish
     */
    public function isPaid(): bool
    {
        // Qoldiq summa 0 ga yaqin bo'lsa, to'langan hisoblanadi
        return $this->getRemainingAmountAttribute() < 0.00001;
    }
}
