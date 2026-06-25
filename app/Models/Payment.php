<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | STATUS PAYMENT
    |--------------------------------------------------------------------------
    */

    public const STATUS_PENDING = 'pending';
    public const STATUS_DITERIMA = 'diterima';
    public const STATUS_DITOLAK = 'ditolak';

    protected $fillable = [
        'appointment_id',
        'amount',
        'payment_method',
        'proof_image',
        'status',
        'verified_by',
        'verified_at',
        'rejection_reason',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'verified_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | STATUS HELPER
    |--------------------------------------------------------------------------
    */

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isAccepted(): bool
    {
        return $this->status === self::STATUS_DITERIMA;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_DITOLAK;
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}