<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImmunizationExportController;
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

    // Registration Desk (Pendaftaran Harian)
    Route::get('/registration-desk', App\Livewire\PatientQueueEntry::class)->name('registration-desk')
        ->middleware('permission:view-all-patients|view-own-patients');

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

    // Delivery Entry (Livewire) - Record delivery data
    Route::get('/pregnancies/{pregnancy}/delivery', App\Livewire\DeliveryEntry::class)->name('pregnancies.delivery')
        ->middleware('permission:create-pregnancies');

    // Postnatal Visit Entry (Livewire) - Record postnatal visits
    Route::get('/pregnancies/{pregnancy}/postnatal', App\Livewire\PostnatalEntry::class)->name('pregnancies.postnatal')
        ->middleware('permission:create-anc-visits');

    // Immunization Module Routes
    Route::get('/children/register', App\Livewire\ChildRegistration::class)->name('children.register')
        ->middleware('permission:create-patients');
    Route::get('/children/{child}', [App\Http\Controllers\ChildController::class, 'show'])->name('children.show')
        ->middleware('permission:view-all-patients|view-own-patients');
    Route::get('/children/{child}/immunization', App\Livewire\ImmunizationEntry::class)->name('children.immunization')
        ->middleware('permission:create-anc-visits');
    Route::get('/children/{child}/visits/{childVisit}', [App\Http\Controllers\ChildController::class, 'showVisit'])->name('children.visit.show')
        ->middleware('permission:view-all-patients|view-own-patients');
    Route::get('/children/{child}/general-visits/{generalVisit}', [App\Http\Controllers\ChildController::class, 'showGeneralVisit'])->name('children.general-visit.show')
        ->middleware('permission:view-all-patients|view-own-patients');

    // Friendly routes for Imunisasi Module (UI/UX)
    Route::get('/imunisasi', App\Livewire\ChildIndex::class)->name('imunisasi.index')
        ->middleware('permission:view-all-patients|view-own-patients');
    Route::get('/imunisasi/daftar', App\Livewire\ChildRegistration::class)->name('imunisasi.register')
        ->middleware('permission:create-patients');
    Route::get('/imunisasi/{child}/kunjungan', App\Livewire\ImmunizationEntry::class)->name('imunisasi.kunjungan')
        ->middleware('permission:create-anc-visits');
    Route::get('/imunisasi/{child}/kunjungan/{childVisit}', [App\Http\Controllers\ChildController::class, 'showVisit'])->name('imunisasi.kunjungan.detail')
        ->middleware('permission:view-all-patients|view-own-patients');


    // KB Visit Detail Routes
    Route::prefix('kb-visits')->name('kb-visits.')->group(function () {
        Route::get('/{kbVisit}', [App\Http\Controllers\KbVisitController::class, 'show'])
            ->name('show')
            ->middleware('permission:view-all-patients|view-own-patients');
        Route::get('/{kbVisit}/edit', [App\Http\Controllers\KbVisitController::class, 'edit'])
            ->name('edit')
            ->middleware('permission:edit-kb');
        Route::delete('/{kbVisit}', [App\Http\Controllers\KbVisitController::class, 'destroy'])
            ->name('destroy')
            ->middleware('permission:delete-anc-visits');
    });

    // Postnatal Visit Detail Routes
    Route::prefix('postnatal-visits')->name('postnatal-visits.')->group(function () {
        Route::get('/{postnatalVisit}', [App\Http\Controllers\PostnatalVisitController::class, 'show'])
            ->name('show')
            ->middleware('permission:view-all-patients|view-own-patients');
        Route::get('/{postnatalVisit}/edit', [App\Http\Controllers\PostnatalVisitController::class, 'edit'])
            ->name('edit')
            ->middleware('permission:create-anc-visits');
        Route::delete('/{postnatalVisit}', [App\Http\Controllers\PostnatalVisitController::class, 'destroy'])
            ->name('destroy')
            ->middleware('permission:delete-anc-visits');
    });

    // ANC Visits Index (All visits)
    Route::get('/anc-visits', App\Livewire\AncVisitIndex::class)->name('anc-visits.index')
        ->middleware('permission:view-all-anc-visits|view-own-anc-visits');

    // Admin: Deleted ANC Visits (Restore functionality)
    Route::get('/admin/deleted-visits', App\Livewire\DeletedAncVisits::class)->name('admin.deleted-visits')
        ->middleware('permission:delete-anc-visits');

    // Admin: Immunization Master Management
    Route::get('/admin/vaccines', App\Livewire\Admin\VaccineManagement::class)->name('admin.vaccines')
        ->middleware('permission:manage-vaccines');
    Route::get('/admin/icd10', App\Livewire\Admin\Icd10Management::class)->name('admin.icd10')
        ->middleware('permission:manage-icd10');

    // Admin: Role & Permission Management
    Route::get('/admin/roles', App\Livewire\RoleManagement::class)->name('admin.roles')
        ->middleware('permission:manage-roles');
    Route::get('/admin/user-roles', App\Livewire\UserRoleManagement::class)->name('admin.user-roles')
        ->middleware('permission:manage-roles');

    // === IMMUNIZATION MODULE (Child & Vaccination) ===

    // Child Registration
    Route::get('/children/create', App\Livewire\ChildRegistration::class)->name('children.create')
        ->middleware('permission:create-patients');

    // Immunization Entry
    Route::get('/children/{child}/immunization', App\Livewire\ImmunizationEntry::class)->name('children.immunization')
        ->middleware('permission:create-anc-visits');

    // === END IMMUNIZATION MODULE ===

    // === GROWTH/KMS MODULE ===
    // Growth Index (Poli Gizi)
    Route::get('/growth', App\Livewire\GrowthIndex::class)->name('growth.index')
        ->middleware('permission:view-growth');

    // Growth Entry (KMS Digital)
    Route::get('/children/{childId}/growth', App\Livewire\GrowthEntry::class)->name('children.growth')
        ->middleware('permission:create-growth');

    // === END GROWTH MODULE ===

    // Export: ANC Register
    Route::get('/export/anc-register', App\Livewire\ExportAncRegister::class)->name('export.anc-register')
        ->middleware('permission:export-data');

    // Export: Patient Master Data
    Route::get('/export/patient-list', App\Livewire\ExportPatientList::class)->name('export.patient-list')
        ->middleware('permission:export-data');

    // Export: General Register (Poli Umum)
    Route::get('/export/general-register', App\Livewire\ExportGeneralRegister::class)->name('export.general-register')
        ->middleware('permission:export-data');

    // Export: Immunization Reports
    Route::get('/export/immunization/monthly', [App\Http\Controllers\ImmunizationExportController::class, 'monthly'])->name('export.immunization.monthly')
        ->middleware('permission:export-data');
    Route::get('/export/immunization/individual', [App\Http\Controllers\ImmunizationExportController::class, 'individual'])->name('export.immunization.individual')
        ->middleware('permission:export-data');

    // Export: Immunization Page
    Route::get('/export/immunization', App\Livewire\ExportImmunization::class)->name('export.immunization.page')
        ->middleware('permission:export-data');

    // Export: Growth Data (New)
    Route::get('/export/growth', App\Livewire\ExportGrowth::class)->name('export.growth')
        ->middleware('permission:export-data');

    // Export: Delivery Register (Register Persalinan)
    Route::get('/export/delivery-register', App\Livewire\ExportDeliveryRegister::class)->name('export.delivery-register')
        ->middleware('permission:export-data');

    // Legacy / alias route for Pendaftaran (kept for sidebar compatibility)
    Route::get('/registration', function () {
        return redirect()->route('patients.create');
    })->name('registration-desk')->middleware('permission:create-patients');

    // Reports: Monthly Summary
    Route::get('/reports/monthly-summary', App\Livewire\MonthlySummaryReport::class)->name('reports.monthly-summary')
        ->middleware('permission:view-reports');

    // Reports: Export PDF (Dedicated Route)
    Route::get('/reports/monthly-summary/export', [App\Http\Controllers\MonthlyReportExportController::class, 'export'])
        ->name('reports.monthly-summary.export')
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

    // KB Module
    Route::get('/kb/entry', App\Livewire\KbEntry::class)->name('kb.entry')
        ->middleware('permission:create-kb');

    // KB Index (List of visits)
    Route::get('/kb', App\Livewire\KbIndex::class)->name('kb.index')
        ->middleware('permission:view-kb');

    // General Visits (Poli Umum)
    Route::get('/general-visits', App\Livewire\GeneralVisitList::class)->name('general-visits.index')
        ->middleware('permission:view-general-visits');

    Route::get('/general-visits/create/{patient_id}', App\Livewire\GeneralVisitEntry::class)->name('general-visits.create')
        ->middleware('permission:create-general-visits');

    // General Visit for Children (Poli Umum Anak) - uses same component with child_id
    Route::get('/general-visits/create-child/{child_id}', App\Livewire\GeneralVisitEntry::class)->name('children.general-visit')
        ->middleware('permission:create-general-visits');

    // Export: KB Reports
    Route::get('/export/kb', App\Livewire\ExportKb::class)->name('export.kb')
        ->middleware('permission:export-data');

    // Admin Routes - User Management
    Route::get('/admin/users', App\Livewire\UserManagement::class)->name('admin.users')
        ->middleware('permission:manage-users');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';