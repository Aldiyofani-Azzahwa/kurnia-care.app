<?php

namespace App\Services;

use App\Models\Appointment;
use Carbon\Carbon;

class AppointmentQuotaService
{
    /**
     * Maksimal pasien per hari.
     */
    private int $maxQuotaPerDay = 10;

    /**
     * Cek apakah tanggal sudah penuh.
     */
    public function isFull(string $date): bool
    {
        return $this->countAppointments($date) >= $this->maxQuotaPerDay;
    }

    /**
     * Hitung jumlah appointment aktif pada tanggal tertentu.
     */
    public function countAppointments(string $date): int
    {
        return Appointment::whereDate('appointment_date', $date)
            ->whereIn('status', [
                Appointment::STATUS_MENUNGGU,
                Appointment::STATUS_DIKONFIRMASI,
            ])
            ->count();
    }

    /**
     * Sisa kuota pada tanggal tertentu.
     */
    public function remainingQuota(string $date): int
    {
        $remaining = $this->maxQuotaPerDay - $this->countAppointments($date);

        return max($remaining, 0);
    }

    /**
     * Cari tanggal terdekat yang masih tersedia.
     */
    public function nearestAvailableDate(?string $startDate = null): ?string
    {
        $date = $startDate
            ? Carbon::parse($startDate)
            : Carbon::today();

        for ($i = 0; $i < 30; $i++) {
            $checkingDate = $date->copy()->addDays($i)->format('Y-m-d');

            if (! $this->isFull($checkingDate)) {
                return $checkingDate;
            }
        }

        return null;
    }

    /**
     * Ambil daftar tanggal tersedia untuk beberapa hari ke depan.
     */
    public function availableDates(int $days = 14): array
    {
        $dates = [];

        for ($i = 0; $i < $days; $i++) {
            $date = Carbon::today()->addDays($i)->format('Y-m-d');

            $dates[] = [
                'date' => $date,
                'remaining_quota' => $this->remainingQuota($date),
                'is_full' => $this->isFull($date),
            ];
        }

        return $dates;
    }
}