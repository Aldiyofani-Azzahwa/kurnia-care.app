<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relasi lama: satu user punya satu patient.
     * Tetap disimpan agar kode lama tidak error.
     */
    public function patient(): HasOne
    {
        return $this->hasOne(Patient::class);
    }

    /**
     * Relasi baru: satu user bisa punya banyak data pasien.
     */
    public function patients(): HasMany
    {
        return $this->hasMany(Patient::class);
    }

    /**
     * Relasi user dokter ke data doctor.
     */
    public function doctor(): HasOne
    {
        return $this->hasOne(Doctor::class);
    }

    /**
     * Relasi pembayaran yang diverifikasi user admin.
     */
    public function verifiedPayments(): HasMany
    {
        return $this->hasMany(Payment::class, 'verified_by');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isDoctor(): bool
    {
        return in_array($this->role, ['dokter', 'doctor'], true);
    }

    public function isUser(): bool
    {
        return in_array($this->role, ['pasien', 'user'], true);
    }

    public function isPasien(): bool
    {
        return $this->isUser();
    }
}