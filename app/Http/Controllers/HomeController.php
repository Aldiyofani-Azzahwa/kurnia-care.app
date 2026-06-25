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
        $today = today();
        $todayDate = $today->toDateString();

        $featuredDoctor = Doctor::with('user')
            ->where('is_active', true)
            ->latest()
            ->first();

        $services = Service::where('is_active', true)
            ->latest()
            ->get();

        $galleries = Gallery::where('is_active', true)
            ->latest()
            ->take(9)
            ->get();

        $testimonials = Testimonial::where('is_active', true)
            ->latest()
            ->take(10)
            ->get();

        $todayBookings = Appointment::whereDate('appointment_date', $todayDate)
            ->whereIn('status', [
                Appointment::STATUS_MENUNGGU,
                Appointment::STATUS_DIKONFIRMASI,
            ])
            ->count();

        $handledPatients = Appointment::where('status', Appointment::STATUS_SELESAI)
            ->count();

        if ($handledPatients < Patient::count()) {
            $handledPatients = Patient::count();
        }

        $clinicEmail = config('mail.from.address');

        if (! $clinicEmail || $clinicEmail === 'hello@example.com') {
            $clinicEmail = 'Email belum diatur';
        }

        $googleMapsEmbedUrl = 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3955.3339217783423!2d112.27570477500251!3d-7.538513192474832!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e786bc7c987ffbd%3A0x854b2e0b2c5fd4ad!2sKurnia%20Care%20-%20Sunat%20Modern%20Jombang!5e0!3m2!1sid!2sid!4v1782407995441!5m2!1sid!2sid';

        $googleMapsDirectionUrl = 'https://www.google.com/maps/search/?api=1&query=Kurnia%20Care%20-%20Sunat%20Modern%20Jombang';

        return view('welcome', [
            'featuredDoctor' => $featuredDoctor,
            'services' => $services,
            'galleries' => $galleries,
            'testimonials' => $testimonials,

            'todayBookings' => $todayBookings,
            'remainingQuota' => $quotaService->remainingQuota($todayDate),
            'handledPatients' => $handledPatients,

            'operationalHours' => 'Senin - Sabtu, 08.00 - 17.00',
            'whatsappNumber' => '0822 8566 2642',
            'whatsappUrl' => 'https://wa.me/6282285662642?text=Halo%20Kurnia%20Care%2C%20saya%20ingin%20konsultasi%20layanan%20khitan.',
            'clinicAddress' => 'Jombang, Jawa Timur',
            'clinicEmail' => $clinicEmail,
            'pricelistImageUrl' => asset('images/pricelist-kurnia-care.jpg'),

            'googleMapsEmbedUrl' => $googleMapsEmbedUrl,
            'googleMapsDirectionUrl' => $googleMapsDirectionUrl,
        ]);
    }
}