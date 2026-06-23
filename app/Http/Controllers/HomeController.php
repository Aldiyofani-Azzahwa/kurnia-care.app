<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Gallery;
use App\Models\Patient;
use App\Models\Service;
use App\Models\Testimonial;
use App\Services\AppointmentQuotaService;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(AppointmentQuotaService $quotaService): View
    {
        $today = today()->format('Y-m-d');

        $featuredDoctor = Doctor::with('user')
            ->where('is_active', true)
            ->latest()
            ->first();

        $services = Service::where('is_active', true)
            ->latest()
            ->take(6)
            ->get();

        $galleries = Gallery::where('is_active', true)
            ->latest()
            ->take(9)
            ->get();

        $testimonials = Testimonial::where('is_active', true)
            ->latest()
            ->take(10)
            ->get();

        $todayBookings = Appointment::whereDate('appointment_date', $today)
            ->whereIn('status', ['menunggu', 'diproses'])
            ->count();

        return view('welcome', [
            'featuredDoctor' => $featuredDoctor,
            'services' => $services,
            'galleries' => $galleries,
            'testimonials' => $testimonials,
            'todayBookings' => $todayBookings,
            'remainingQuota' => $quotaService->remainingQuota($today),
            'handledPatients' => max(1000, Patient::count()),
            'operationalHours' => 'Senin - Sabtu, 08.00 - 17.00',
            'whatsappNumber' => '0822 8566 2642',
            'whatsappUrl' => 'https://wa.me/6282285662642?text=Halo%20Kurnia%20Care%2C%20saya%20ingin%20konsultasi%20layanan%20khitan.',
            'clinicAddress' => 'Jombang, Jawa Timur',
            'clinicEmail' => config('mail.from.address') !== 'hello@example.com'
                ? config('mail.from.address')
                : 'Email belum diatur',
                'pricelistImageUrl' => asset('images/pricelist-kurnia-care.jpg'),
                'pricelistImageUrl' => asset('images/pricelist-kurnia-care.jpg'),
        ]);
    }
}