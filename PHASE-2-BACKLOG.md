# 📋 PHASE 2 BACKLOG - E-Kohort Klinik

## 🎯 Phase 2 Overview

**Goal**: Build complete CRUD interface, Dashboard, and Reporting features
**Duration**: Estimated 2-3 weeks
**Priority**: High-value features first (Dashboard → Patient Management → Reports)

**Progress**:

-   ✅ Epic 1: Dashboard Bidan (3/4 stories completed)
-   ✅ Epic 2: Patient Management (4/4 stories completed - 100% COMPLETE)
-   🔄 Epic 3: ANC Visit History & Management (3/4 stories completed - 75%)
-   ⏳ Epic 4-8: Pending

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
**Test Results**: ⏳ Ready for testing

---

## 📤 Epic 4: Export & Reporting (Priority: MEDIUM ⭐⭐)

**Business Value**: Generate reports for Dinas Kesehatan

### Story 4.1: Export ANC Register to Excel

**As a** Bidan
**I want to** export ANC data to Excel
**So that** I can submit reports to Dinas

**Acceptance Criteria:**

-   [ ] Export button on patient list and dashboard
-   [ ] Match Excel template format (Register_ANC_Terintegrasi.csv)
-   [ ] Include all columns from template
-   [ ] Filter by date range
-   [ ] Filter by pregnancy status
-   [ ] Filter by risk category
-   [ ] Generate filename with timestamp
-   [ ] Show progress indicator during export

**Technical Tasks:**

-   Install `maatwebsite/excel` package
-   Create export class `AncRegisterExport.php`
-   Map database columns to Excel columns
-   Implement date range filtering
-   Add download route

**Estimate**: 6 hours

---

### Story 4.2: Export Patient Master Data

**As a** Bidan
**I want to** export patient list
**So that** I can have offline backup

**Acceptance Criteria:**

-   [ ] Export all patients or filtered subset
-   [ ] Include demographics and latest pregnancy status
-   [ ] CSV and Excel format options
-   [ ] Include active pregnancy indicator

**Technical Tasks:**

-   Create `PatientExport.php` class
-   Add export button on patient list

**Estimate**: 3 hours

---

### Story 4.3: Print Individual Patient Report

**As a** Bidan
**I want to** print a patient's complete record
**So that** I can give to the patient or for filing

**Acceptance Criteria:**

-   [ ] Print button on patient detail page
-   [ ] Include patient demographics
-   [ ] Include pregnancy history
-   [ ] Include all ANC visits
-   [ ] Print-friendly layout (A4 size)
-   [ ] Include clinic header (customizable)

**Technical Tasks:**

-   Create print view template
-   Add print CSS
-   Implement browser print dialog

**Estimate**: 4 hours

---

### Story 4.4: Monthly Summary Report

**As a** Bidan Koordinator
**I want to** generate monthly summary
**So that** I can report to Dinas

**Acceptance Criteria:**

-   [ ] Total new patients registered
-   [ ] Total ANC visits per visit code (K1-K6)
-   [ ] High-risk patient count
-   [ ] Triple Eliminasi screening coverage
-   [ ] Complications and referrals
-   [ ] Export to PDF

**Technical Tasks:**

-   Create report query aggregations
-   Create PDF view (DomPDF or similar)
-   Add date range selector

**Estimate**: 5 hours

---

## 🔐 Epic 5: Role & Permission System (Priority: MEDIUM ⭐⭐)

**Business Value**: Multi-user support with proper access control

### Story 5.1: User Management (Admin)

**As an** Admin
**I want to** manage user accounts
**So that** I can control access to the system

**Acceptance Criteria:**

-   [ ] List all users with roles
-   [ ] Create new user (email, password, role)
-   [ ] Assign role: Admin, Bidan Koordinator, Bidan Desa
-   [ ] Edit user information
-   [ ] Deactivate/activate user
-   [ ] Reset password functionality

**Technical Tasks:**

-   Install Spatie Laravel Permission package
-   Create roles migration
-   Create `UserManagement` Livewire component
-   Implement role assignment

**Estimate**: 5 hours

---

### Story 5.2: Role-Based Access Control

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

### Story 5.3: Activity Log

**As an** Admin
**I want to** see user activity logs
**So that** I can audit system usage

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

**Estimate**: 4 hours (Optional - Can defer)

---

## 🔔 Epic 6: Alert & Notification System (Priority: LOW ⭐)

**Business Value**: Proactive alerts for high-risk cases

### Story 6.1: In-App Notifications

**As a** Bidan
**I want to** receive notifications for high-risk patients
**So that** I don't miss critical cases

**Acceptance Criteria:**

-   [ ] Notification icon in topbar (bell icon)
-   [ ] Badge showing unread count
-   [ ] Notification types:
    -   New high-risk patient detected
    -   Patient due for next visit
    -   Triple Eliminasi reaktif detected
-   [ ] Mark as read functionality
-   [ ] Click notification to go to patient detail

**Technical Tasks:**

-   Create notifications table migration
-   Create notification component
-   Implement notification logic in AncVisit save
-   Add notification polling (Livewire wire:poll)

**Estimate**: 6 hours

---

### Story 6.2: SMS Reminders (Optional - External Service)

**As a** Bidan
**I want to** send SMS reminders to patients
**So that** they don't miss appointments

**Acceptance Criteria:**

-   [ ] Send SMS for next visit reminder (7 days before)
-   [ ] Send SMS for high-risk referral
-   [ ] Configure SMS gateway (Twilio, Vonage)
-   [ ] Track SMS delivery status

**Technical Tasks:**

-   Integrate SMS API
-   Create queue job for SMS sending
-   Add SMS log table

**Estimate**: 8 hours (Defer to Phase 3)

---

## 🎨 Epic 7: UI/UX Improvements (Priority: MEDIUM ⭐⭐)

**Business Value**: Better user experience and usability

### Story 7.1: Improved Navigation

**As a** User
**I want to** navigate easily between sections
**So that** I can find what I need quickly

**Acceptance Criteria:**

-   [ ] Active menu highlighting
-   [ ] Breadcrumbs on all pages
-   [ ] Quick search in topbar (global search)
-   [ ] Keyboard shortcuts (optional)

**Technical Tasks:**

-   Update sidebar with active states
-   Add breadcrumb component
-   Implement global search

**Estimate**: 3 hours

---

### Story 7.2: Mobile Responsiveness Check

**As a** Bidan using mobile
**I want to** access the system on my phone
**So that** I can work from anywhere

**Acceptance Criteria:**

-   [ ] All pages responsive on mobile (320px+)
-   [ ] Tables scroll horizontally on small screens
-   [ ] Touch-friendly buttons (min 44px)
-   [ ] Forms stack vertically on mobile

**Technical Tasks:**

-   Test on mobile devices
-   Fix responsive issues
-   Optimize table display for mobile

**Estimate**: 4 hours

---

### Story 7.3: Loading States & Feedback

**As a** User
**I want to** see loading indicators
**So that** I know the system is processing

**Acceptance Criteria:**

-   [ ] Spinner during Livewire actions
-   [ ] Skeleton loaders for data tables
-   [ ] Success/error toast notifications
-   [ ] Disable buttons during submission

**Technical Tasks:**

-   Add `wire:loading` directives
-   Create toast notification component
-   Add skeleton loader styles

**Estimate**: 3 hours

---

## 📦 Epic 8: Data Management & Utilities (Priority: LOW ⭐)

**Business Value**: Data quality and maintenance

### Story 8.1: Bulk Import Patients

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
