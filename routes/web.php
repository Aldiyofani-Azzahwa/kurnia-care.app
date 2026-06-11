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


/*
|--------------------------------------------------------------------------
| PUBLIC ROUTE
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

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

    $user = auth()->user();

    if ($user->role === 'admin') {
        return redirect('/admin/dashboard');
    }

    if ($user->role === 'dokter') {
        return redirect('/doctor/dashboard');
    }

    return redirect('/user/dashboard');

})->middleware(['auth', 'verified'])->name('dashboard');

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
    | ADMIN PAYMENTS
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/payments', [AdminPaymentController::class, 'index'])
        ->name('admin.payments.index');

    Route::get('/admin/payments/{payment}', [AdminPaymentController::class, 'show'])
        ->name('admin.payments.show');

    Route::post('/admin/payments/{payment}/verify', [AdminPaymentController::class, 'verify'])
        ->name('admin.payments.verify');

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

Route::middleware(['auth', 'role:user'])->group(function () {

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