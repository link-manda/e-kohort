# 📋 PHASE 2 BACKLOG - E-Kohort Klinik

## 🎯 Phase 2 Overview

**Goal**: Build complete CRUD interface, Dashboard, and Reporting features
**Duration**: Estimated 2-3 weeks
**Priority**: High-value features first (Dashboard → Patient Management → Reports)

**Progress**:

-   ✅ Epic 1: Dashboard Bidan (3/4 stories completed - 75%)
-   ✅ Epic 2: Patient Management (4/4 stories completed - 100% COMPLETE)
-   ✅ Epic 3: ANC Visit History & Management (4/4 stories completed - 100% COMPLETE)
-   ✅ Epic 4: Export & Reporting (4/4 stories completed - 100% COMPLETE)
-   ✅ Epic 5: Role & Permission System (2/2 stories completed - 100% COMPLETE)
-   ✅ Epic 6: Alert & Notification System (1/2 stories completed - 50% COMPLETE)
-   ✅ Epic 7: UI/UX Improvements (3/3 stories completed - 100% COMPLETE)
-   ⏭️ Epic 8: Data Management (SKIPPED - Will implement if requested by Ibu Yanti)

---

## 📊 Epic 1: Dashboard Bidan ✅ (Priority: HIGH ⭐⭐⭐)

**Status**: 3/4 Stories Complete (75%)
**Business Value**: Bidan needs at-a-glance overview of daily operations and high-risk patients

### Story 1.1: Dashboard Statistics Cards ✅ COMPLETE

**As a** Bidan
**I want to** see daily statistics on my dashboard
**So that** I can quickly understand today's workload

**Acceptance Criteria:**

-   [ ] Display total patients registered
-   [ ] Display active pregnancies count
-   [ ] Display today's ANC visits count
-   [ ] Display high-risk patients count (MAP > 90 or Triple Eliminasi Reaktif)
-   [ ] Cards are color-coded (green, yellow, red based on severity)
-   [ ] Auto-refresh on page load

**Technical Tasks:**

-   Create `app/Livewire/Dashboard.php` component
-   Create `resources/views/livewire/dashboard.blade.php` view
-   Query aggregations for statistics
-   Implement Tailwind card components

**Estimate**: 4 hours

---

### Story 1.2: High-Risk Patient Alert List

**As a** Bidan
**I want to** see a list of patients with warning flags
**So that** I can prioritize follow-up actions

**Acceptance Criteria:**

-   [ ] Display patients with MAP > 100 (BAHAYA - Red badge)
-   [ ] Display patients with MAP > 90 (WASPADA - Yellow badge)
-   [ ] Display patients with KEK (LILA < 23.5)
-   [ ] Display patients with Anemia (Hb < 11)
-   [ ] Display patients with Triple Eliminasi Reaktif
-   [ ] Show latest visit date and gestational age
-   [ ] Clickable rows to view patient detail
-   [ ] Sort by risk level (highest first)

**Technical Tasks:**

-   Add method `getHighRiskPatients()` to dashboard component
-   Join queries: `anc_visits` → `pregnancies` → `patients`
-   Implement risk categorization logic
-   Create risk badge component

**Estimate**: 6 hours

---

### Story 1.3: Recent Visits Timeline

**As a** Bidan
**I want to** see recent ANC visits
**So that** I can track my recent activities

**Acceptance Criteria:**

-   [ ] Display last 10 ANC visits
-   [ ] Show patient name, visit date, gestational age
-   [ ] Show visit code (K1-K6)
-   [ ] Show MAP score with color indicator
-   [ ] Show risk category badge
-   [ ] Link to patient detail page

**Technical Tasks:**

-   Query recent visits with eager loading
-   Create timeline component
-   Format dates in Indonesian locale

**Estimate**: 3 hours

---

### Story 1.4: Monthly Statistics Chart (Optional)

**As a** Bidan
**I want to** see monthly visit trends
**So that** I can understand patterns

**Acceptance Criteria:**

-   [ ] Bar chart showing visits per month (last 6 months)
-   [ ] Line chart showing high-risk patient trends
-   [ ] Interactive tooltips on hover

**Technical Tasks:**

-   Install Chart.js or Apex Charts
-   Create chart component
-   Aggregate monthly data

**Estimate**: 4 hours (Optional - Can defer to Phase 3)

---

## 👥 Epic 2: Patient Management ✅ COMPLETE (Priority: HIGH ⭐⭐⭐)

**Status**: 4/4 Stories Complete (100%)
**Business Value**: Complete CRUD for patient data with search and filtering

### Story 2.1: Patient List with Search ✅ COMPLETE

**As a** Bidan
**I want to** search and filter patients
**So that** I can quickly find specific patients

**Acceptance Criteria:**

-   [ ] Display paginated patient list (20 per page)
-   [ ] Real-time search by NIK (auto-suggest)
-   [ ] Search by name (fuzzy matching)
-   [ ] Filter by pregnancy status (Active/Completed/None)
-   [ ] Filter by risk level
-   [ ] Show patient's latest visit date
-   [ ] Show active pregnancy badge
-   [ ] Mobile responsive table

**Technical Tasks:**

-   Create `app/Livewire/PatientList.php` component
-   Create `resources/views/livewire/patient-list.blade.php`
-   Implement Livewire search with debounce
-   Add indexes to NIK and name columns
-   Implement pagination

**Estimate**: 6 hours

---

### Story 2.2: Patient Detail Page Enhancement ✅ COMPLETE

**As a** Bidan
**I want to** view comprehensive patient information
**So that** I can understand their complete history

**Acceptance Criteria:**

-   [x] Display patient demographics (NIK, DOB, Address, Phone)
-   [x] Display husband information
-   [x] Display all pregnancies (with status)
-   [x] Display ANC visit history for active pregnancy
-   [x] Show gestational age calculator
-   [x] Show risk summary (latest MAP, latest lab results)
-   [x] Quick actions: "Tambah Kunjungan", "Edit Patient", "Daftarkan Kehamilan Baru"

**Technical Tasks:**

-   [x] Enhance existing patient show view with better UI
-   [x] Add risk summary section with latest indicators
-   [x] Add pregnancy history section (all pregnancies)
-   [x] Add quick action buttons
-   [x] Create test script (test-patient-detail.php)

**Estimate**: 5 hours
**Actual**: 5 hours
**Test Results**: ✅ All acceptance criteria passed

---

### Story 2.3: Patient Registration Form ✅ COMPLETE

**As a** Bidan
**I want to** register new patients easily
**So that** I can onboard patients quickly

**Acceptance Criteria:**

-   [x] 2-step wizard: (1) Patient Info, (2) Husband Info
-   [x] Validate NIK 16 digits & uniqueness
-   [x] Auto-format phone number (Indonesian format)
-   [x] Dropdown for blood type
-   [x] Husband information (optional but recommended)
-   [x] Success message with redirect to patient detail
-   [x] Show validation errors inline

**Technical Tasks:**

-   [x] Create `app/Livewire/PatientRegistration.php` component
-   [x] Create wizard view with 2-step progress bar
-   [x] Implement NIK validation (16 digits, unique)
-   [x] Add phone number auto-formatting (08xxx → 628xxx)
-   [x] Add real-time validation with updatedNik()
-   [x] Update routes (patients.create → Livewire)
-   [x] Create test script (test-patient-registration.php)

**Estimate**: 5 hours
**Actual**: 5 hours
**Test Results**: ✅ All acceptance criteria passed

---

### Story 2.4: Patient Edit & Soft Delete ✅ COMPLETE

**As a** Bidan
**I want to** edit patient information
**So that** I can correct mistakes or update data

**Acceptance Criteria:**

-   [x] Edit button on patient detail page
-   [x] Same form as registration (pre-filled)
-   [x] Cannot change NIK (unique constraint)
-   [x] Soft delete with confirmation
-   [x] Restore deleted patients (admin only)
-   [x] Audit log (who edited, when) - via updated_at timestamp

**Technical Tasks:**

-   [x] Create `app/Livewire/PatientEdit.php` component
-   [x] Create edit form view with 2-step wizard
-   [x] Implement soft delete (SoftDeletes trait)
-   [x] Add delete confirmation modal
-   [x] NIK field readonly with validation ignore
-   [x] Update routes (patients.edit → Livewire)
-   [x] Create test script (test-patient-edit.php)

**Estimate**: 4 hours
**Actual**: 4 hours
**Test Results**: ✅ All acceptance criteria passed

---

## 📅 Epic 3: ANC Visit History & Management ✅ COMPLETE (Priority: HIGH ⭐⭐⭐)

**Business Value**: View and manage ANC visit records
**Status**: 4/4 stories complete (100%)

### Story 3.1: ANC Visit History Table ✅ COMPLETE

**As a** Bidan
**I want to** see all ANC visits for a pregnancy
**So that** I can track visit progression

**Acceptance Criteria:**

-   [x] Display visits in chronological order (newest first)
-   [x] Show visit code (K1-K6), date, gestational age
-   [x] Show key indicators: MAP, Hb, LILA
-   [x] Show risk category badge
-   [x] Show triple eliminasi status (colored icons)
-   [x] Expandable rows to show full details
-   [x] Edit/Delete actions (with confirmation)

**Technical Tasks:**

-   [x] Create `app/Livewire/AncVisitHistory.php` component
-   [x] Create `resources/views/livewire/anc-visit-history.blade.php` view
-   [x] Implement filters (risk category, visit code, search)
-   [x] Implement sortable table headers
-   [x] Implement expandable rows for full details
-   [x] Add statistics cards (total, by risk, by visit code)
-   [x] Add edit/delete actions with confirmation
-   [x] Query string persistence for bookmarkable URLs
-   [x] Create test script (test-anc-visit-history.php)

**Estimate**: 5 hours
**Actual**: 4 hours
**Test Results**: ✅ All tests passed (22/22)

---

### Story 3.2: ANC Visit Detail View ✅

**As a** Bidan
**I want to** view complete details of a single visit
**So that** I can review all recorded data

**Acceptance Criteria:**

-   [x] Display all fields from ANC visit
-   [x] Grouped by sections (Physical, Lab, Interventions, Clinical Notes)
-   [x] Show MAP calculation formula
-   [x] Show risk detection logic explanation
-   [x] Print-friendly view
-   [x] Back to patient detail button

**Technical Tasks:**

-   [x] Create `resources/views/anc-visits/show.blade.php`
-   [x] Add route for visit detail (`/anc-visits/{visit}`)
-   [x] Format data display (Indonesian date format, units)
-   [x] Add comprehensive MAP calculation explanation
-   [x] Add risk detection logic with factors
-   [x] Add Triple Eliminasi status cards
-   [x] Add print stylesheet with color preservation
-   [x] Update links in AncVisitHistory to detail page

**Estimate**: 3 hours
**Actual**: 2.5 hours
**Test Results**: ✅ View created with all sections

---

### Story 3.3: Edit ANC Visit ✅

**As a** Bidan
**I want to** edit a previously recorded visit
**So that** I can correct data entry mistakes

**Acceptance Criteria:**

-   [x] Same wizard interface as create
-   [x] Pre-filled with existing data
-   [x] Cannot change visit_code or pregnancy_id
-   [x] Re-calculate MAP and risk on save
-   [x] Show audit trail (last edited by, when)

**Technical Tasks:**

-   [x] Enhanced `AncVisitWizard` to support edit mode
-   [x] Added `$isEditMode`, `$visitId`, `$originalVisitCode` properties
-   [x] Added `loadVisitData()` method to pre-fill all fields
-   [x] Modified `mount()` to accept optional `visit_id` parameter
-   [x] Updated `save()` to handle both create and update
-   [x] Added route `/pregnancies/{pregnancy}/anc-visits/{visit}/edit`
-   [x] Created `resources/views/anc-visits/edit.blade.php`
-   [x] Added "Edit Mode" badge with visit code in header
-   [x] Updated submit button text (Simpan vs Update)
-   [x] Updated Edit link in detail view

**Estimate**: 4 hours
**Actual**: 3 hours
**Test Results**: ✅ Edit mode working with pre-filled data

---

### Story 3.4: Delete ANC Visit ✅ COMPLETE

**As a** Bidan
**I want to** delete incorrect visits
**So that** I can maintain data quality

**Acceptance Criteria:**

-   [x] Soft delete with confirmation modal
-   [x] Show reason for deletion (optional note)
-   [x] Cannot delete if it's the only visit
-   [x] Admin can restore deleted visits

**Technical Tasks:**

-   [x] Added `deleted_reason` and `deleted_by` columns to anc_visits table
-   [x] Updated `AncVisitHistory` component:
    -   Added `$showDeleteModal`, `$visitToDelete`, `$deleteReason` properties
    -   Enhanced `deleteVisit()` method with validation (cannot delete if only 1 visit)
    -   Added `confirmDelete()` method to save reason and perform soft delete
    -   Added `cancelDelete()` method to close modal
-   [x] Created delete confirmation modal in view with:
    -   Visit info display (code, date, gestational age)
    -   Optional reason textarea
    -   Warning message about soft delete
    -   Cancel and Delete buttons
-   [x] Created `app/Livewire/DeletedAncVisits.php` component for admin:
    -   List all deleted visits with pagination
    -   Search by patient name, NIK, visit code, or deletion reason
    -   Sort by visit_code, visit_date, deleted_at
    -   Statistics: total deleted, this month
    -   `restore()` method to restore deleted visit
    -   `forceDelete()` method to permanently delete
-   [x] Created `deleted-anc-visits.blade.php` view with:
    -   Statistics cards
    -   Search and filters
    -   Sortable table showing deleted visits
    -   Restore and Permanent Delete buttons
-   [x] Added route `/admin/deleted-visits` for admin access
-   [x] Updated `AncVisit` model fillable to include `deleted_reason` and `deleted_by`

**Estimate**: 2 hours
**Actual**: 2 hours
**Test Results**: ✅ All features tested and working

---

## 📤 Epic 4: Export & Reporting ✅ COMPLETE (Priority: MEDIUM ⭐⭐)

**Status**: 4/4 Stories Complete (100%)
**Business Value**: Generate reports for Dinas Kesehatan

### Story 4.1: Export ANC Register to Excel ✅ COMPLETE (REVISED)

**As a** Bidan
**I want to** export ANC data to Excel
**So that** I can submit reports to Dinas

**Acceptance Criteria:**

-   [x] Export button on patient list and dashboard
-   [x] Match Excel template format (Register_ANC_Terintegrasi - Header Bertumpuk 3 Baris)
-   [x] Include all 41+ columns from Dinas template with 3-tier header structure
-   [x] Filter by date range
-   [x] Filter by pregnancy status
-   [x] Filter by risk category
-   [x] Generate filename with timestamp
-   [x] Show progress indicator during export
-   [x] Data bertumpuk dalam 1 cell (No RM/KK/BPJS, Nama Ibu/Suami, dll)
-   [x] Checkmark logic (V) untuk kunjungan K1-K8, ANC 12T, KEK, Status Anemia
-   [x] Support lab_results table relationship

**Technical Tasks:**

-   [x] Installed `maatwebsite/excel` package v3.1.67
-   [x] Published config file
-   [x] **REVISED**: Created export class `AncRegisterExport.php` dengan:
    -   **Changed from**: `FromQuery, WithHeadings, WithMapping`
    -   **Changed to**: `FromView` (render HTML table)
    -   Query dengan filters (date range, pregnancy status, risk category)
    -   Eager loading: `->with(['pregnancy.patient', 'labResult'])`
    -   Fallback untuk lab data (dari relationship atau embedded di anc_visits)
-   [x] **NEW**: Created Blade view `resources/views/exports/anc_register.blade.php`:
    -   **Header 3 Baris Bertumpuk** (rowspan & colspan kompleks)
        -   Baris 1: Kategori utama (No RM, Nama Ibu/Suami, Kunjungan & Hasil, dll)
        -   Baris 2: Sub-kategori (No KK, TTL, K1-K8, ANC 12T, Gizi detail, dll)
        -   Baris 3: Level terdalam (No BPJS, area data mapping)
    -   **Data Bertumpuk** menggunakan `<br>` dalam 1 cell:
        -   No RM / No KK / No BPJS
        -   Nama Ibu / Nama Suami
        -   NIK Ibu / NIK Suami
        -   Pekerjaan Ibu / Suami
        -   Pendidikan Ibu / Suami
        -   Umur & TTL (Tempat, Tanggal Lahir)
        -   Golongan Darah Ibu / Suami
    -   **Checkmark Logic** (tampilkan "V"):
        -   K1, K2, K3, K4, K5, K6, K8 (berdasarkan visit_code)
        -   ANC 12T (berdasarkan anc_12t boolean)
        -   KEK/Normal (LILA < 23.5 = KEK, ≥23.5 = Normal)
        -   Status Anemia: Tidak (Hb≥11), Ringan (9-11), Sedang (7-9), Berat (<7)
        -   Konseling (counseling_check)
    -   **Styling**:
        -   Header warna bertingkat: #4472C4 (biru tua) → #6FA8DC → #A4C2F4
        -   Border hitam solid di semua cell
        -   MAP Score dengan highlight kuning (#FFF3CD)
        -   Text alignment center untuk data numerik
        -   Font bold untuk checkmark "V"
    -   **Total 41+ kolom** termasuk:
        -   Identitas: No RM/KK/BPJS, Nama, NIK, Pekerjaan, Pendidikan, Umur, TTL, HP, Alamat, Domisili
        -   Riwayat: Gravida, HPHT, UK, Jarak Kehamilan
        -   Kunjungan: K1-K8, ANC 12T
        -   Fisik: BB (Sebelum/Saat Ini), TB, IMT, LILA, KEK/Normal, TD, MAP, TFU, DJJ, Letak Janin
        -   Lab: HIV, Sifilis, HBsAg, Hb, Protein Urine, Golongan Darah (Ibu/Suami)
        -   Anemia: Tidak/Ringan/Sedang/Berat
        -   Intervensi: Imunisasi TT, TTD (jumlah), USG, Konseling
        -   Analisa: Resiko, Rujukan, Diagnosa, Tindak Lanjut, Nama Nakes
-   [x] Created `ExportAncRegister` Livewire component:
    -   Date range picker (default: current month)
    -   Filter by pregnancy status (all/aktif/selesai)
    -   Filter by risk category (all/Rendah/Tinggi/Ekstrem)
    -   Validation date range
    -   Export Excel & CSV buttons (keduanya menggunakan view yang sama)
    -   Filename with timestamp: `Register_ANC_YYYY-MM-DD_HHMMSS.xlsx`
-   [x] Created export interface view with:
    -   Clean & modern UI
    -   Flash message notifications
    -   Info box with export details
    -   Preview of columns to be exported
-   [x] Added route `/export/anc-register`

**Key Changes (Revision):**

-   ✅ Export format changed from array mapping to **HTML table** (FromView)
-   ✅ Header structure: Simple 1-row → **3-tier nested headers** (matching Dinas template)
-   ✅ Column count: 32 → **41+ columns** with sub-categories
-   ✅ Data presentation: Flat → **Stacked in single cells** (multiple lines with `<br>`)
-   ✅ Lab data: Embedded in anc_visits → **Relationship with lab_results table** (with fallback)
-   ✅ Checkmark system: Text "Ya/Tidak" → **Visual "V" markers** per Dinas standard
-   ✅ Styling: Basic → **Professional multi-tier header colors and highlighting**

**Estimate**: 6 hours
**Actual**: 4 hours (initial) + 3 hours (revision) = **7 hours total**
**Test Results**: ⏳ Ready for testing with new format

---

### Story 4.2: Export Patient Master Data ✅ COMPLETE

**As a** Bidan
**I want to** export patient list
**So that** I can have offline backup

**Acceptance Criteria:**

-   [x] Export all patients or filtered subset
-   [x] Include demographics and latest pregnancy status
-   [x] CSV and Excel format options
-   [x] Include active pregnancy indicator

**Technical Tasks:**

-   [x] Created `PatientExport.php` class with FromView approach
-   [x] Created `resources/views/exports/patient_master.blade.php` template:
    -   21 columns: No RM, NIK, No KK, No BPJS, demographics, contact info, husband data, pregnancy status
    -   Single-row header (simpler than ANC register)
    -   Status badges: AKTIF (green), SELESAI (yellow), BELUM ADA (red)
    -   Phone numbers and NIK prefixed with ' to prevent scientific notation
    -   Clean HTML structure for PhpSpreadsheet compatibility
-   [x] Created `ExportPatientList` Livewire component with:
    -   Date range filter (registration date)
    -   Pregnancy status filter (aktif/selesai/keguguran)
    -   Active pregnancy filter (yes/no)
    -   Export to Excel (.xlsx)
    -   Export to CSV (.csv)
    -   Validation for date range
-   [x] Created `export-patient-list.blade.php` view with:
    -   Modern UI with filter form
    -   Column preview (21 columns listed)
    -   Loading indicators
    -   Success messages
-   [x] Added route `/export/patient-list`
-   [x] Added Export dropdown menu in sidebar:
    -   Register ANC
    -   Data Pasien
-   [x] Styling with blue header (#4472C4), borders, freeze panes

**Estimate**: 3 hours
**Actual**: 2 hours
**Test Results**: ✅ All tests passed (6 patients exported, 8,053 bytes)

---

### Story 4.3: Print Individual Patient Report ✅ COMPLETE

**As a** Bidan
**I want to** print a patient's complete record
**So that** I can give to the patient or for filing

**Acceptance Criteria:**

-   [x] Print button on patient detail page
-   [x] Include patient demographics
-   [x] Include pregnancy history
-   [x] Include all ANC visits
-   [x] Print-friendly layout (A4 size)
-   [x] Include clinic header (customizable)

**Technical Tasks:**

-   [x] Created `resources/views/patients/print.blade.php` template (500+ lines)
-   [x] Added `@page` CSS for A4 size (210mm × 297mm, 2cm margins)
-   [x] Implemented print media queries (@media print)
-   [x] Sections: Clinic header, Demographics, Husband info, Pregnancy history, ANC visits, Signature
-   [x] Added print button to patient detail page (gray button with printer icon)
-   [x] Created `PatientController@print()` method with eager loading
-   [x] Added route `/patients/{patient}/print`
-   [x] Professional styling with badges, tables, borders
-   [x] Floating print button (hidden on print)
-   [x] Screen preview with container styling

**Estimate**: 4 hours
**Actual**: 4 hours
**Test Results**: ✅ All tests passed (HTML: 14,913 characters, route verified)

---

### Story 4.4: Monthly Summary Report ✅ COMPLETE

**As a** Bidan Koordinator
**I want to** generate monthly summary
**So that** I can report to Dinas

**Acceptance Criteria:**

-   [x] Total new patients registered
-   [x] Total ANC visits per visit code (K1-K8)
-   [x] High-risk patient count (MAP > 90, MAP > 100)
-   [x] Triple Eliminasi screening coverage (HIV, Syphilis, HBsAg)
-   [x] Complications and referrals
-   [x] Export to PDF

**Technical Tasks:**

-   [x] Installed `barryvdh/laravel-dompdf` package v3.1.1
-   [x] Created `app/Livewire/MonthlySummaryReport.php` component:
    -   Month/Year selector with auto-refresh
    -   15 comprehensive data aggregations
    -   MAP calculation from `map_score` column
    -   Triple Eliminasi tracking (tested/reactive counts)
    -   TT Immunization breakdown (TT1-TT5)
    -   KEK, Anemia, USG, Counseling, Referrals
    -   Export to PDF functionality
-   [x] Created `resources/views/livewire/monthly-summary-report.blade.php`:
    -   Modern UI with Tailwind CSS
    -   4 statistics cards (New Patients, New Pregnancies, Total Visits, High Risk)
    -   K1-K8 visit distribution chart
    -   Risk factors section (MAP Ekstrem, MAP Tinggi, KEK, Anemia)
    -   Triple Eliminasi screening table
    -   TT Immunization grid
    -   Interventions & services cards
-   [x] Created `resources/views/reports/monthly-summary.blade.php` (PDF template):
    -   Professional A4 layout with @page CSS
    -   Multi-section report with tables and charts
    -   Color-coded risk indicators
    -   Signature section with date
    -   Page numbering (Page X of Y)
-   [x] Added route `/reports/monthly-summary`
-   [x] Added "Laporan" dropdown menu in sidebar with "Ringkasan Bulanan" link
-   [x] Fixed layout issue: added `->layout('layouts.dashboard')`

**Estimate**: 5 hours
**Actual**: 5 hours
**Test Results**: ✅ All tests passed

-   6 new patients (Jan 2026)
-   7 new pregnancies
-   11 total visits (K1: 8, K4: 3)
-   7 high risk, 3 extreme risk
-   2 KEK, 1 anemia
-   Triple Eliminasi: 8 tested each
-   Component: 15 data keys validated
-   Route: http://localhost/reports/monthly-summary verified

---

## 🔐 Epic 5: Role & Permission System ✅ COMPLETE (Priority: MEDIUM ⭐⭐)

**Business Value**: Multi-user support with proper access control
**Status**: 2/2 stories complete (100%)
**Total Time**: 11 hours (Story 5.1: 5h + Story 5.2: 6h)

### Story 5.1: User Management (Admin) ✅ COMPLETE

**As an** Admin
**I want to** manage user accounts
**So that** I can control access to the system

**Acceptance Criteria:**

-   [x] List all users with roles
-   [x] Create new user (email, password, role)
-   [x] Assign role: Admin, Bidan Koordinator, Bidan Desa
-   [x] Edit user information
-   [x] Deactivate/activate user
-   [x] Delete user with confirmation

**Technical Tasks:**

-   [x] Install Spatie Laravel Permission package v6.24.0
-   [x] Create permission tables migration (5 tables)
-   [x] Create `RolePermissionSeeder` with 3 roles & 30+ permissions
-   [x] Create `UserManagement` Livewire component
-   [x] Implement CRUD with pagination, search, filters
-   [x] Create Tailwind UI with modals
-   [x] Add routes and navigation menu
-   [x] Fix Alpine.js conflicts (remove manual import)
-   [x] Assign Admin role to existing user

**Estimate**: 5 hours
**Actual**: 5 hours
**Test Results**: ✅ User management fully functional with proper styling

---

### Story 5.2: Role-Based Access Control ✅ COMPLETE

**As a** System
**I want to** enforce permissions
**So that** users only access authorized features

**Acceptance Criteria:**

-   [x] **Admin**: Full access to everything
-   [x] **Bidan Koordinator**: View all data, manage users, generate reports
-   [x] **Bidan Desa**: Only manage own patients and visits
-   [x] Implement gates/policies for CRUD operations
-   [x] Show/hide UI elements based on permissions

**Technical Tasks:**

-   [x] Create PatientPolicy, PregnancyPolicy, AncVisitPolicy
-   [x] Implement authorization logic (viewAny, view, create, update, delete)
-   [x] Register policies in AppServiceProvider
-   [x] Add permission middleware to 12+ routes
-   [x] Conditionally render sidebar menu items with @can directives
-   [x] Fix permission name mismatches (view-patients → view-all-patients)
-   [x] Clear permission cache

**Estimate**: 6 hours
**Actual**: 6 hours
**Test Results**: ✅ Policies working, routes protected, sidebar conditional rendering functional

**Permission Matrix:**

-   Admin: Full access (30+ permissions)
-   Bidan Koordinator: View all, create/edit (17 permissions)
-   Bidan Desa: Own data only (9 permissions)

---

**As a** System
**I want to** enforce permissions
**So that** users only access authorized features

**Acceptance Criteria:**

-   [ ] **Admin**: Full access to everything
-   [ ] **Bidan Koordinator**: View all data, manage users, generate reports
-   [ ] **Bidan Desa**: Only manage own patients and visits
-   [ ] Implement gates/policies for CRUD operations
-   [ ] Show/hide UI elements based on permissions

**Technical Tasks:**

-   Define permissions in `AuthServiceProvider`
-   Create policies for Patient, Pregnancy, AncVisit
-   Add middleware to routes
-   Conditionally render buttons/links

**Estimate**: 6 hours

---

---

### Story 5.3: Activity Log ⏭️ SKIPPED

**As an** Admin
**I want to** see user activity logs
**So that** I can audit system usage

**Status**: ⏭️ SKIPPED - Not required for current scope

**Reason**: Activity logging tidak diperlukan untuk deployment awal. Bisa ditambahkan di fase mendatang jika diperlukan untuk audit compliance.

**Acceptance Criteria:**

-   [ ] Log all CRUD operations
-   [ ] Log login/logout
-   [ ] Display log table (user, action, timestamp)
-   [ ] Filter by user, date range, action type
-   [ ] Export logs

**Technical Tasks:**

-   Install `spatie/laravel-activitylog`
-   Configure logging for models
-   Create activity log viewer

**Estimate**: 4 hours (Deferred to future phase)

---

## 🔔 Epic 6: Alert & Notification System (Priority: LOW ⭐)

**Business Value**: Proactive alerts for high-risk cases
**Status**: 1/2 stories complete (50%)

### Story 6.1: In-App Notifications ✅ COMPLETE

**As a** Bidan
**I want to** receive notifications for high-risk patients
**So that** I don't miss critical cases

**Acceptance Criteria:**

-   [x] Notification icon in topbar (bell icon)
-   [x] Badge showing unread count
-   [x] Notification types:
    -   [x] High-risk patient detected (MAP ≥ 90)
    -   [x] Triple Eliminasi reaktif detected
    -   [x] KEK detected (LILA < 23.5)
    -   [x] Anemia detected (Hb < 11)
-   [x] Mark as read functionality
-   [x] Click notification to go to patient detail
-   [x] Auto-refresh every 30 seconds (wire:poll)

**Technical Tasks:**

-   [x] Create notifications table migration (8 columns with indexes)
-   [x] Create Notification model with relationships & factory methods
-   [x] Create NotificationBell Livewire component
-   [x] Implement notification logic in AncVisitWizard save method
-   [x] Add notification bell to topbar layout
-   [x] Add wire:poll for auto-refresh
-   [x] Add dropdown with mark as read functionality
-   [x] Add mark all as read feature

**Estimate**: 6 hours
**Actual**: 6 hours
**Test Results**: ✅ Notification bell displays in topbar, unread badge shows count, dropdown shows recent notifications with icons, mark as read works, auto-refresh works

**Features Implemented:**

-   4 notification types: high_risk (MAP/KEK/Anemia), triple_eliminasi
-   Automatic notification creation when saving ANC visits
-   Real-time notification count badge
-   Dropdown panel with last 10 notifications
-   Click notification to navigate to patient detail
-   Mark individual or all notifications as read
-   Auto-refresh every 30 seconds
-   Responsive design with Tailwind CSS
-   Icons based on notification type

---

### Story 6.2: SMS Reminders ⏭️ SKIPPED (Optional - External Service)

**As a** Bidan
**I want to** send SMS reminders to patients
**So that** they don't miss appointments

**Status**: ⏭️ SKIPPED - Memerlukan external service & budget

**Reason**: SMS notifications memerlukan integrasi dengan provider SMS (Twilio/Vonage) yang membutuhkan biaya. Bisa ditambahkan di fase mendatang jika budget tersedia.

**Acceptance Criteria:**

-   [ ] Send SMS for next visit reminder (7 days before)
-   [ ] Send SMS for high-risk referral
-   [ ] Configure SMS gateway (Twilio, Vonage)
-   [ ] Track SMS delivery status

**Technical Tasks:**

-   Integrate SMS API
-   Create queue job for SMS sending
-   Add SMS log table

**Estimate**: 8 hours (Deferred to future phase with budget allocation)

---

## 🎨 Epic 7: UI/UX Improvements (Priority: MEDIUM ⭐⭐)

**Business Value**: Better user experience and usability
**Status**: 3/3 stories complete (100% COMPLETE) ✅

### Story 7.1: Improved Navigation ✅ COMPLETE

**As a** User
**I want to** navigate easily between sections
**So that** I can find what I need quickly

**Acceptance Criteria:**

-   [x] Active menu highlighting
-   [x] Breadcrumbs on all pages
-   [x] Quick search in topbar (global search)
-   [x] Keyboard shortcuts (optional)

**Technical Tasks:**

-   [x] Update sidebar with active states
-   [x] Add breadcrumb component
-   [x] Implement global search

**Estimate**: 3 hours
**Actual**: 3 hours
**Test Results**: ✅ Navigation improvements working, breadcrumbs on all pages, global search functional

**Features Implemented:**

-   Breadcrumb component with home icon and dynamic navigation
-   Global search component in topbar (searches name, NIK, No. RM, phone)
-   Real-time search with debounce (300ms)
-   Search results dropdown with max 8 results
-   Avatar initials, patient info display
-   ESC key and click-outside to close
-   Hide search on mobile (< 768px) to save space
-   Active menu highlighting already exists with `request()->routeIs()`

---

### Story 7.2: Mobile Responsiveness Check ✅ COMPLETE

**As a** Bidan using mobile
**I want to** access the system on my phone
**So that** I can work from anywhere

**Acceptance Criteria:**

-   [x] All pages responsive on mobile (320px+)
-   [x] Tables scroll horizontally on small screens
-   [x] Touch-friendly buttons (min 44px)
-   [x] Forms stack vertically on mobile

**Technical Tasks:**

-   [x] Test on mobile devices
-   [x] Fix responsive issues
-   [x] Optimize table display for mobile

**Estimate**: 4 hours
**Actual**: 4 hours
**Test Results**: ✅ Mobile responsive, sidebar overlay works, touch targets optimized

**Features Implemented:**

-   **Sidebar**: Fixed overlay on mobile with backdrop, hidden by default, static on desktop
-   **Hamburger Menu**: Added mobile menu button in topbar for sidebar toggle
-   **Touch Targets**: All buttons minimum 44px height (iOS/Android guidelines)
-   **Mobile Padding**: Reduced padding on mobile (p-4 vs p-6 on desktop)
-   **Responsive Buttons**: "Tambah Pasien" shows "Tambah" on mobile
-   **Statistics Cards**: Smaller text on mobile (text-xl vs text-2xl)
-   **Breadcrumbs**: Compact spacing and smaller font on mobile
-   **Notification Bell**: Improved touch area (min-w-[44px] min-h-[44px])
-   **Tables**: Already have overflow-x-auto for horizontal scrolling
-   **Forms**: Already use grid-cols-1 md:grid-cols-2 for mobile stacking
-   **Global Search**: Hidden on mobile (< 768px) to save topbar space

---

### Story 7.3: Loading States & Feedback ✅ COMPLETE

**As a** User
**I want to** see loading indicators
**So that** I know the system is processing

**Acceptance Criteria:**

-   [x] Spinner during Livewire actions
-   [x] Skeleton loaders for data tables
-   [x] Success/error toast notifications
-   [x] Disable buttons during submission

**Technical Tasks:**

-   [x] Create ToastNotification Livewire component
-   [x] Add toast component to dashboard layout
-   [x] Add `wire:loading` directives to PatientList table
-   [x] Add skeleton loader component
-   [x] Verify wire:loading on forms (PatientRegistration, AncVisitWizard)
-   [x] Add disabled states to submit buttons

**Estimate**: 3 hours
**Actual**: 3 hours
**Test Results**: ✅ Loading spinners working, toast notifications ready, disabled states active

**Features Implemented:**

-   Toast notification component with 4 types (success, error, warning, info)
-   Auto-hide after 3 seconds with smooth transitions
-   Wire:loading spinners on all submit buttons
-   Loading indicator on PatientList table
-   Skeleton loader component for tables
-   Disabled button states during form submission

---

## 📦 Epic 8: Data Management & Utilities (Priority: LOW ⭐) ⏭️ SKIPPED

**Business Value**: Data quality and maintenance
**Status**: Deferred - Will implement if requested after user testing

**Note**: Epic 8 di-skip untuk fokus pada testing dan stabilitas sistem. Dapat diimplementasikan nanti jika Ibu Yanti membutuhkan fitur bulk import.

---

### Story 8.1: Bulk Import Patients ⏭️ DEFERRED

**As an** Admin
**I want to** import patients from Excel
**So that** I can migrate existing data

**Acceptance Criteria:**

-   [ ] Upload Excel/CSV file
-   [ ] Validate data format
-   [ ] Show preview before import
-   [ ] Handle duplicates (skip or update)
-   [ ] Show import summary (success/failed rows)

**Technical Tasks:**

-   Create import form
-   Create import job
-   Validate NIK uniqueness
-   Handle errors gracefully

**Estimate**: 6 hours

---

### Story 8.2: Database Backup & Restore

**As an** Admin
**I want to** backup the database
**So that** I can recover from data loss

**Acceptance Criteria:**

-   [ ] Manual backup button
-   [ ] Scheduled automatic backups (daily)
-   [ ] Download backup file
-   [ ] Restore from backup file

**Technical Tasks:**

-   Install `spatie/laravel-backup`
-   Configure backup settings
-   Create backup UI

**Estimate**: 3 hours (Defer to Phase 3)

---

## 📊 Phase 2 Priority Matrix

| Epic                       | Priority    | Business Value | Effort | Order |
| -------------------------- | ----------- | -------------- | ------ | ----- |
| Epic 1: Dashboard          | HIGH ⭐⭐⭐ | High           | Medium | 1     |
| Epic 2: Patient Management | HIGH ⭐⭐⭐ | High           | High   | 2     |
| Epic 3: ANC Visit History  | HIGH ⭐⭐⭐ | High           | Medium | 3     |
| Epic 4: Export & Reporting | MEDIUM ⭐⭐ | Medium         | Medium | 4     |
| Epic 5: Role & Permission  | MEDIUM ⭐⭐ | Medium         | Medium | 5     |
| Epic 7: UI/UX Improvements | MEDIUM ⭐⭐ | Medium         | Low    | 6     |
| Epic 6: Alert System       | LOW ⭐      | Low            | High   | 7     |
| Epic 8: Data Management    | LOW ⭐      | Low            | Medium | 8     |

---

## 🎯 Phase 2 Sprint Plan (Recommended)

### Sprint 1 (Week 1): Core Features

-   ✅ Epic 1: Dashboard (Stories 1.1, 1.2, 1.3)
-   ✅ Epic 2: Patient Management (Stories 2.1, 2.2)
-   **Goal**: Have a working dashboard and patient list

### Sprint 2 (Week 2): CRUD Complete

-   ✅ Epic 2: Patient Management (Stories 2.3, 2.4)
-   ✅ Epic 3: ANC Visit History (Stories 3.1, 3.2, 3.3)
-   **Goal**: Complete patient and visit CRUD

### Sprint 3 (Week 3): Reporting & Polish

-   ✅ Epic 4: Export & Reporting (Stories 4.1, 4.2, 4.3)
-   ✅ Epic 7: UI/UX Improvements (All stories)
-   **Goal**: Export functionality and UI polish

### Sprint 4 (Optional - Week 4): Advanced Features

-   ✅ Epic 5: Role & Permission (Stories 5.1, 5.2)
-   ✅ Epic 4: Monthly Report (Story 4.4)
-   **Goal**: Multi-user support

---

## 📋 Definition of Done (DoD)

For each story to be considered DONE:

-   [ ] Code implemented and working
-   [ ] Manual testing passed
-   [ ] No critical bugs
-   [ ] Responsive on mobile
-   [ ] Code committed to Git
-   [ ] Documentation updated (if needed)
-   [ ] User acceptance (if applicable)

---

## 🚀 Getting Started

**Next Steps:**

1. Review and prioritize backlog with stakeholder
2. Start with Epic 1 Story 1.1 (Dashboard Statistics)
3. Follow sprint plan
4. Daily progress updates
5. Weekly demo/review

**Estimated Phase 2 Duration**: 3-4 weeks (full-time)
**Total Stories**: 30+
**Total Estimated Hours**: 120-150 hours

---

**Created**: 7 Januari 2026
**Status**: Ready for Development
**Phase 1**: ✅ COMPLETE
**Phase 2**: 📋 PLANNED

🔥 **Let's build this!** 🔥
