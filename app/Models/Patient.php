<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'registered_by_id',
        'registration_type',

        'child_name',
        'child_age',
        'child_weight',
        'drug_allergy',
        'bleeding_history',
        'surgery_history',
        'disease_history',
        'address',

        'province_code',
        'province_name',
        'city_code',
        'city_name',
        'district_code',
        'district_name',
        'village_code',
        'village_name'
        ,
        'father_name',
        'mother_name',
        'phone',
        'instagram',
        'facebook',
        'information_source',
        'child_photo',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function registeredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registered_by_id');
    }
}