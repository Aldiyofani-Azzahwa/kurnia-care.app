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

    public function patient(): HasOne
    {
        return $this->hasOne(Patient::class);
    }

    public function patients(): HasMany
    {
        return $this->hasMany(Patient::class);
    }

    public function doctor(): HasOne
    {
        return $this->hasOne(Doctor::class);
    }

    public function verifiedPayments(): HasMany
    {
        return $this->hasMany(Payment::class, 'verified_by');
    }

    public function normalizedRole(): ?string
    {
        $role = $this->role ? strtolower(trim($this->role)) : null;

        return match ($role) {
            'admin' => 'admin',
            'dokter', 'doctor' => 'dokter',
            'pasien', 'user' => 'pasien',
            default => null,
        };
    }

    public function isAdmin(): bool
    {
        return $this->normalizedRole() === 'admin';
    }

    public function isDoctor(): bool
    {
        return $this->normalizedRole() === 'dokter';
    }

    public function isDokter(): bool
    {
        return $this->isDoctor();
    }

    public function isUser(): bool
    {
        return $this->normalizedRole() === 'pasien';
    }

    public function isPasien(): bool
    {
        return $this->isUser();
    }
}