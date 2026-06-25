<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Appointment extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | STATUS APPOINTMENT
    |--------------------------------------------------------------------------
    */

    public const STATUS_MENUNGGU = 'menunggu';
    public const STATUS_DIKONFIRMASI = 'dikonfirmasi';
    public const STATUS_SELESAI = 'selesai';
    public const STATUS_DIBATALKAN = 'dibatalkan';

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'service_id',
        'schedule_id',
        'appointment_date',
        'appointment_day',
        'appointment_time',
        'medicine_type',
        'circumcision_package',
        'status',
        'admin_note',
    ];

    protected $casts = [
        'appointment_date' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | STATUS HELPER
    |--------------------------------------------------------------------------
    */

    public function isWaiting(): bool
    {
        return $this->status === self::STATUS_MENUNGGU;
    }

    public function isConfirmed(): bool
    {
        return $this->status === self::STATUS_DIKONFIRMASI;
    }

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_SELESAI;
    }

    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_DIBATALKAN;
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    /*
    |--------------------------------------------------------------------------
    | MEDICAL NOTE
    |--------------------------------------------------------------------------
    | Secara bisnis, satu appointment idealnya punya satu catatan tindakan utama.
    | Method medicalNotes tetap disediakan agar kode lama tidak langsung error.
    */

    public function medicalNote(): HasOne
    {
        return $this->hasOne(MedicalNote::class);
    }

    public function medicalNotes(): HasMany
    {
        return $this->hasMany(MedicalNote::class);
    }
}