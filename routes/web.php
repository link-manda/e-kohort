<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Redirect root to dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Protected Routes (Requires Authentication)
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Patient Management
    Route::resource('patients', PatientController::class);

    // Pregnancy Registration (Livewire)
    Route::get('/patients/{patient}/pregnancies/create', function ($patient) {
        return view('pregnancies.create', ['patient_id' => $patient]);
    })->name('pregnancies.create');

    // ANC Visit Wizard (Livewire)
    Route::get('/pregnancies/{pregnancy}/anc-visits/create', function ($pregnancy) {
        return view('anc-visits.create', ['pregnancy_id' => $pregnancy]);
    })->name('anc-visits.create');

    // ANC Visits (placeholder routes)
    Route::get('/anc-visits', function () {
        return view('dashboard', ['message' => 'Kunjungan ANC - Coming Soon']);
    })->name('anc-visits.index');

    // Reports (placeholder routes)
    Route::get('/reports', function () {
        return view('dashboard', ['message' => 'Laporan - Coming Soon']);
    })->name('reports.index');

    // Alerts (placeholder routes)
    Route::get('/alerts', function () {
        return view('dashboard', ['message' => 'Alert Risiko - Coming Soon']);
    })->name('alerts.index');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
