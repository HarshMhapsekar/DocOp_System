<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PharmacistController;
use App\Http\Controllers\SearchController;

// Public Static & Information Pages
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/services', [HomeController::class, 'services'])->name('services');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Dedicated Patient Login View
Route::get('/patient/login', [AuthController::class, 'showPatientLogin'])->name('patient.login.view');

// Dedicated Hospital Staff & Clinician Portal
Route::get('/staff/login', [AuthController::class, 'showStaffLogin'])->name('staff.login.view');
Route::get('/admin/login', fn () => redirect()->route('staff.login.view', ['role' => 'admin']));
Route::get('/doctor/login', fn () => redirect()->route('staff.login.view', ['role' => 'doctor']));
Route::get('/pharmacist/login', fn () => redirect()->route('staff.login.view', ['role' => 'pharmacist']));

// Unified Authentication Actions
Route::post('/auth/patient/register', [AuthController::class, 'registerPatient'])->name('auth.patient.register');
Route::post('/auth/patient/login', [AuthController::class, 'loginPatient'])->name('auth.patient.login');
Route::post('/auth/doctor/login', [AuthController::class, 'loginDoctor'])->name('auth.doctor.login');
Route::post('/auth/admin/login', [AuthController::class, 'loginAdmin'])->name('auth.admin.login');
Route::post('/auth/pharmacist/login', [AuthController::class, 'loginPharmacist'])->name('auth.pharmacist.login');
Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('auth.logout');

// Unified Search Hub
Route::match(['get', 'post'], '/search', [SearchController::class, 'search'])->name('search');

// Administrator Portal
Route::prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::post('/doctor', [AdminController::class, 'addDoctor'])->name('admin.doctor.add');
    Route::post('/doctor/delete', [AdminController::class, 'deleteDoctor'])->name('admin.doctor.delete');
    Route::post('/payment/update', [AdminController::class, 'updatePayment'])->name('admin.payment.update');
});

// Doctor Portal
Route::prefix('doctor')->group(function () {
    Route::get('/', [DoctorController::class, 'index'])->name('doctor.dashboard');
    Route::get('/prescribe', [DoctorController::class, 'prescribe'])->name('doctor.prescribe');
    Route::post('/prescribe', [DoctorController::class, 'storePrescription'])->name('doctor.prescribe.store');
    Route::match(['get', 'post'], '/cancel/{id}', [DoctorController::class, 'cancelAppointment'])->name('doctor.appointment.cancel');
});

// Patient Portal
Route::prefix('patient')->group(function () {
    Route::get('/', [PatientController::class, 'index'])->name('patient.dashboard');
    Route::get('/booked-slots', [PatientController::class, 'getBookedSlots'])->name('patient.booked.slots');
    Route::post('/ehr', [PatientController::class, 'updateEHR'])->name('patient.ehr.update');
    Route::post('/document', [PatientController::class, 'addDocument'])->name('patient.document.add');
    Route::post('/appointment', [PatientController::class, 'bookAppointment'])->name('patient.appointment.book');
    Route::match(['get', 'post'], '/cancel/{id}', [PatientController::class, 'cancelAppointment'])->name('patient.appointment.cancel');
});

// Pharmacist Portal
Route::prefix('pharmacist')->group(function () {
    Route::get('/', [PharmacistController::class, 'index'])->name('pharmacist.dashboard');
    Route::post('/medicine', [PharmacistController::class, 'addMedicine'])->name('pharmacist.medicine.add');
    Route::post('/medicine/restock', [PharmacistController::class, 'restockMedicine'])->name('pharmacist.medicine.restock');
    Route::match(['get', 'post'], '/medicine/{id}/delete', [PharmacistController::class, 'deleteMedicine'])->name('pharmacist.medicine.delete');
});

// Backward Compatibility for Legacy URLs & Cached Browsers
Route::match(['get', 'post'], '/func.php', function (\Illuminate\Http\Request $request) {
    if ($request->isMethod('post')) {
        if ($request->has('patsub') || ($request->has('email') && ($request->has('password') || $request->has('password2')))) {
            return app(AuthController::class)->loginPatient($request);
        }
        if ($request->has('docsub') || $request->has('username3')) {
            return app(AuthController::class)->loginDoctor($request);
        }
        if ($request->has('adsub')) {
            return app(AuthController::class)->loginAdmin($request);
        }
    }
    if (session('role') === 'patient') return redirect()->route('patient.dashboard');
    if (session('role') === 'doctor') return redirect()->route('doctor.dashboard');
    if (session('role') === 'admin') return redirect()->route('admin.dashboard');
    return redirect()->route('home');
});

Route::match(['get', 'post'], '/func1.php', fn () => redirect()->route('home'));
Route::match(['get', 'post'], '/func2.php', fn () => redirect()->route('home'));
Route::match(['get', 'post'], '/func3.php', fn () => redirect()->route('home'));
Route::match(['get', 'post'], '/logout.php', [AuthController::class, 'logout']);

Route::get('/admin-panel.php', function () {
    if (session('role') === 'admin') return redirect()->route('admin.dashboard');
    if (session('role') === 'patient') return redirect()->route('patient.dashboard');
    return redirect()->route('home');
});
Route::get('/admin-panel1.php', fn () => redirect()->route('admin.dashboard'));
Route::get('/doctor-panel.php', fn () => redirect()->route('doctor.dashboard'));
Route::get('/pharmacist.php', fn () => redirect()->route('pharmacist.dashboard'));
Route::get('/index1.php', fn () => redirect()->route('patient.login.view'));
Route::get('/services.html', fn () => redirect()->route('services'));
Route::get('/about.html', fn () => redirect()->route('about'));
Route::get('/contact.html', fn () => redirect()->route('contact'));
