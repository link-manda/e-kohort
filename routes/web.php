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

    // Dashboard (Livewire)
    Route::get('/dashboard', App\Livewire\Dashboard::class)->name('dashboard');

    // Patient Management (Livewire List, Registration & Edit)
    Route::get('/patients', App\Livewire\PatientList::class)->name('patients.index')
        ->middleware('permission:view-all-patients|view-own-patients');
    Route::get('/patients/create', App\Livewire\PatientRegistration::class)->name('patients.create')
        ->middleware('permission:create-patients');
    Route::get('/patients/{patient}', [PatientController::class, 'show'])->name('patients.show')
        ->middleware('permission:view-all-patients|view-own-patients');
    Route::get('/patients/{patient}/edit', App\Livewire\PatientEdit::class)->name('patients.edit')
        ->middleware('permission:edit-patients|view-own-patients');
    Route::get('/patients/{patient}/print', [PatientController::class, 'print'])->name('patients.print')
        ->middleware('permission:view-all-patients|view-own-patients');

    // Pregnancy Registration (Livewire)
    Route::get('/patients/{patient}/pregnancies/create', function ($patient) {
        return view('pregnancies.create', ['patient_id' => $patient]);
    })->name('pregnancies.create')
        ->middleware('permission:create-pregnancies');

    // ANC Visit Wizard (Livewire) - Create
    Route::get('/pregnancies/{pregnancy}/anc-visits/create', function ($pregnancy) {
        return view('anc-visits.create', ['pregnancy_id' => $pregnancy]);
    })->name('anc-visits.create')
        ->middleware('permission:create-anc-visits');

    // ANC Visit Edit (Livewire)
    Route::get('/pregnancies/{pregnancy}/anc-visits/{visit}/edit', function ($pregnancy, $visit) {
        return view('anc-visits.edit', ['pregnancy_id' => $pregnancy, 'visit_id' => $visit]);
    })->name('anc-visits.edit')
        ->middleware('permission:edit-anc-visits|view-own-anc-visits');

    // ANC Visit History (Livewire)
    Route::get('/pregnancies/{pregnancy}/anc-visits', App\Livewire\AncVisitHistory::class)->name('anc-visits.history')
        ->middleware('permission:view-all-anc-visits|view-own-anc-visits');

    // ANC Visit Detail
    Route::get('/anc-visits/{visit}', function ($visit) {
        $visit = \App\Models\AncVisit::findOrFail($visit);
        return view('anc-visits.show', ['visit' => $visit]);
    })->name('anc-visits.show')
        ->middleware('permission:view-all-anc-visits|view-own-anc-visits');

    // ANC Visits Index (All visits)
    Route::get('/anc-visits', App\Livewire\AncVisitIndex::class)->name('anc-visits.index')
        ->middleware('permission:view-all-anc-visits|view-own-anc-visits');

    // Admin: Deleted ANC Visits (Restore functionality)
    Route::get('/admin/deleted-visits', App\Livewire\DeletedAncVisits::class)->name('admin.deleted-visits')
        ->middleware('permission:delete-anc-visits');

    // Export: ANC Register
    Route::get('/export/anc-register', App\Livewire\ExportAncRegister::class)->name('export.anc-register')
        ->middleware('permission:export-data');

    // Export: Patient Master Data
    Route::get('/export/patient-list', App\Livewire\ExportPatientList::class)->name('export.patient-list')
        ->middleware('permission:export-data');

    // Reports: Monthly Summary
    Route::get('/reports/monthly-summary', App\Livewire\MonthlySummaryReport::class)->name('reports.monthly-summary')
        ->middleware('permission:view-reports');

    // Reports (redirect to dashboard for now)
    Route::get('/reports', function () {
        return redirect()->route('dashboard')->with('info', 'Fitur laporan akan segera hadir.');
    })->name('reports.index')
        ->middleware('permission:view-reports');

    // Alerts (redirect to dashboard for now)
    Route::get('/alerts', function () {
        return redirect()->route('dashboard')->with('info', 'Fitur alert risiko akan segera hadir.');
    })->name('alerts.index');

    // Admin Routes - User Management
    Route::get('/admin/users', App\Livewire\UserManagement::class)->name('admin.users')
        ->middleware('permission:manage-users');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
