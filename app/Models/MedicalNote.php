<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicalNote extends Model
{
    use HasFactory;

    public const ACTION_BERHASIL = 'berhasil';
    public const ACTION_PERLU_KONTROL = 'perlu_kontrol';
    public const ACTION_GAGAL = 'gagal';
    public const ACTION_LAINNYA = 'lainnya';

    public const ACTION_STATUSES = [
        self::ACTION_BERHASIL,
        self::ACTION_PERLU_KONTROL,
        self::ACTION_GAGAL,
        self::ACTION_LAINNYA,
    ];

    protected $fillable = [
        'appointment_id',
        'doctor_id',
        'note',
        'action_status',
    ];

    public function isSuccessful(): bool
    {
        return $this->action_status === self::ACTION_BERHASIL;
    }

    public function needsControl(): bool
    {
        return $this->action_status === self::ACTION_PERLU_KONTROL;
    }

    public function isFailed(): bool
    {
        return $this->action_status === self::ACTION_GAGAL;
    }

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }
}