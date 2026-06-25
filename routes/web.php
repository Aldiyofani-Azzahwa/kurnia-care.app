<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\User\PaymentController as UserPaymentController;
use App\Http\Controllers\User\AppointmentController as UserAppointmentController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Doctor\AppointmentController as DoctorAppointmentController;
use App\Http\Controllers\Doctor\MedicalNoteController as DoctorMedicalNoteController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Doctor\DashboardController as DoctorDashboardController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\Admin\PatientController as AdminPatientController;
use App\Http\Controllers\Admin\DoctorController as AdminDoctorController;
use App\Http\Controllers\Admin\ScheduleController as AdminScheduleController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\TestimonialController as AdminTestimonialController;
use App\Http\Controllers\Admin\GalleryController as AdminGalleryController;


/*
|--------------------------------------------------------------------------
| PUBLIC ROUTE
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index'])->name('home');

/*
|--------------------------------------------------------------------------
| REGION ROUTE
|--------------------------------------------------------------------------
*/

Route::get('/regions/provinces', [RegionController::class, 'provinces'])
    ->name('regions.provinces');

Route::get('/regions/cities/{provinceCode}', [RegionController::class, 'cities'])
    ->name('regions.cities');

Route::get('/regions/districts/{cityCode}', [RegionController::class, 'districts'])
    ->name('regions.districts');

Route::get('/regions/villages/{districtCode}', [RegionController::class, 'villages'])
    ->name('regions.villages');

/*
|--------------------------------------------------------------------------
| REDIRECT DASHBOARD BERDASARKAN ROLE
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    $role = auth()->user()->role;

    return match ($role) {
        'admin' => redirect()->route('admin.dashboard'),
        'dokter' => redirect()->route('doctor.dashboard'),
        'pasien', 'user' => redirect()->route('user.dashboard'),
        default => abort(403, 'Role akun tidak dikenali.'),
    };
})->middleware(['auth'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| PROFILE ROUTE
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTE
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
        ->name('admin.dashboard');

    /*
    |--------------------------------------------------------------------------
    | ADMIN PATIENTS / PASIEN
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/patients/check-quota', [AdminPatientController::class, 'checkQuota'])
        ->name('admin.patients.checkQuota');

    Route::resource('/admin/patients', AdminPatientController::class)
        ->names('admin.patients');


    /*
|--------------------------------------------------------------------------
| ADMIN DOCTORS / DOKTER
|--------------------------------------------------------------------------
*/
    Route::resource('/admin/doctors', AdminDoctorController::class)
        ->names('admin.doctors');

    /*
/*
|--------------------------------------------------------------------------
| ADMIN SCHEDULES / JADWAL PASIEN
|--------------------------------------------------------------------------
*/
    Route::get('/admin/schedules', [AdminScheduleController::class, 'index'])
        ->name('admin.schedules.index');

    Route::get('/admin/schedules/{appointment}', [AdminScheduleController::class, 'show'])
        ->name('admin.schedules.show');

    Route::patch('/admin/schedules/{appointment}/status', [AdminScheduleController::class, 'updateStatus'])
        ->name('admin.schedules.updateStatus');

    /*
    |--------------------------------------------------------------------------
    | ADMIN REPORTS / LAPORAN
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/reports', [AdminReportController::class, 'index'])
        ->name('admin.reports.index');

    Route::get('/admin/reports/print', [AdminReportController::class, 'print'])
        ->name('admin.reports.print');


    /*
|--------------------------------------------------------------------------
| ADMIN PAYMENTS / PEMBAYARAN
|--------------------------------------------------------------------------
*/

    Route::get('/admin/payments', [AdminPaymentController::class, 'index'])
        ->name('admin.payments.index');

    Route::get('/admin/payments/{payment}', [AdminPaymentController::class, 'show'])
        ->name('admin.payments.show');

    /*
    | Route lama tetap dipertahankan agar tombol/view lama tidak error.
    | Method verify di controller akan diarahkan ke accept().
    */
    Route::post('/admin/payments/{payment}/verify', [AdminPaymentController::class, 'verify'])
        ->name('admin.payments.verify');

    /*
    | Route baru yang lebih jelas secara proses bisnis.
    | Bisa dipakai nanti kalau tombol di view mau diganti dari verify ke accept.
    */
    Route::post('/admin/payments/{payment}/accept', [AdminPaymentController::class, 'accept'])
        ->name('admin.payments.accept');

    Route::post('/admin/payments/{payment}/reject', [AdminPaymentController::class, 'reject'])
        ->name('admin.payments.reject');
    /*
    |--------------------------------------------------------------------------
    | ADMIN SERVICES / LAYANAN
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/services', [AdminServiceController::class, 'index'])
        ->name('admin.services.index');

    Route::get('/admin/services/create', [AdminServiceController::class, 'create'])
        ->name('admin.services.create');

    Route::post('/admin/services', [AdminServiceController::class, 'store'])
        ->name('admin.services.store');

    Route::get('/admin/services/{service}/edit', [AdminServiceController::class, 'edit'])
        ->name('admin.services.edit');

    Route::put('/admin/services/{service}', [AdminServiceController::class, 'update'])
        ->name('admin.services.update');

    Route::delete('/admin/services/{service}', [AdminServiceController::class, 'destroy'])
        ->name('admin.services.destroy');

    /*
|--------------------------------------------------------------------------
| ADMIN SERVICES / TESTIMONIAL
|--------------------------------------------------------------------------
*/


    Route::get('/admin/testimonials', [AdminTestimonialController::class, 'index'])
        ->name('admin.testimonials.index');

    Route::get('/admin/testimonials/create', [AdminTestimonialController::class, 'create'])
        ->name('admin.testimonials.create');

    Route::post('/admin/testimonials', [AdminTestimonialController::class, 'store'])
        ->name('admin.testimonials.store');

    Route::get('/admin/testimonials/{testimonial}/edit', [AdminTestimonialController::class, 'edit'])
        ->name('admin.testimonials.edit');

    Route::put('/admin/testimonials/{testimonial}', [AdminTestimonialController::class, 'update'])
        ->name('admin.testimonials.update');

    Route::delete('/admin/testimonials/{testimonial}', [AdminTestimonialController::class, 'destroy'])
        ->name('admin.testimonials.destroy');

    /*
   |--------------------------------------------------------------------------
|   ADMIN SERVICES / DOKUMENTASI
   |--------------------------------------------------------------------------
   */

    Route::get('/admin/galleries', [AdminGalleryController::class, 'index'])
        ->name('admin.galleries.index');

    Route::get('/admin/galleries/create', [AdminGalleryController::class, 'create'])
        ->name('admin.galleries.create');

    Route::post('/admin/galleries', [AdminGalleryController::class, 'store'])
        ->name('admin.galleries.store');

    Route::get('/admin/galleries/{gallery}/edit', [AdminGalleryController::class, 'edit'])
        ->name('admin.galleries.edit');

    Route::put('/admin/galleries/{gallery}', [AdminGalleryController::class, 'update'])
        ->name('admin.galleries.update');

    Route::delete('/admin/galleries/{gallery}', [AdminGalleryController::class, 'destroy'])
        ->name('admin.galleries.destroy');

});
/*
|--------------------------------------------------------------------------
| DOCTOR ROUTE
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:dokter'])->group(function () {

    Route::get('/doctor/dashboard', [DoctorDashboardController::class, 'index'])
        ->name('doctor.dashboard');

    Route::get('/doctor/appointments', [DoctorAppointmentController::class, 'index'])
        ->name('doctor.appointments.index');

    Route::get('/doctor/appointments/{appointment}', [DoctorAppointmentController::class, 'show'])
        ->name('doctor.appointments.show');

    Route::post('/doctor/appointments/{appointment}/medical-notes', [DoctorMedicalNoteController::class, 'store'])
        ->name('doctor.medical-notes.store');

    Route::get('/doctor/history', [DoctorAppointmentController::class, 'history'])
        ->name('doctor.appointments.history');

    Route::get('/doctor/medical-notes', [DoctorAppointmentController::class, 'medicalNotes'])
        ->name('doctor.medical-notes.index');

});

/*
|--------------------------------------------------------------------------
| USER / PASIEN ROUTE
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:pasien'])->group(function () {

    Route::get('/user/dashboard', [UserDashboardController::class, 'index'])
        ->name('user.dashboard');

    Route::get('/user/appointments', [UserAppointmentController::class, 'index'])
        ->name('user.appointments.index');

    Route::get('/user/appointments/create', [UserAppointmentController::class, 'create'])
        ->name('user.appointments.create');

    Route::post('/user/appointments', [UserAppointmentController::class, 'store'])
        ->name('user.appointments.store');

    Route::get('/user/appointments/{appointment}', [UserAppointmentController::class, 'show'])
        ->name('user.appointments.show');

    Route::get('/user/check-quota', [UserAppointmentController::class, 'checkQuota'])
        ->name('user.checkQuota');

    Route::get('/user/appointments/{appointment}/payment', [UserPaymentController::class, 'edit'])
        ->name('user.payments.edit');

    Route::post('/user/appointments/{appointment}/payment', [UserPaymentController::class, 'update'])
        ->name('user.payments.update');

});

/*
|--------------------------------------------------------------------------
| AUTH ROUTE
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';