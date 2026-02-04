# **ROADMAP: TRANSFORMASI MENUJU E-PUSKESMAS (UNIVERSAL PATIENT)**

**Goal Utama:** Mengubah sistem dari "Centric Ibu Hamil" menjadi "Patient Centric". **Filosofi:** Satu Pasien (NIK) $\\rightarrow$ Bisa mengakses banyak layanan (Umum, KIA, KB, Anak) seumur hidupnya.

## **📅 PHASE 1: DATABASE FOUNDATION (Pondasi Data)** ✅ SELESAI

_Tujuan: Menyiapkan "wadah" data agar bisa menampung Bapak-bapak, Anak-anak, dan Lansia tanpa merusak data Ibu Hamil yang sudah ada._

- **\[✅\] Refactor Tabel patients (Generalisasi)**
    - ✅ Tambah kolom `gender` (Enum: 'L', 'P'). Default 'P' untuk backward compatibility.
    - ✅ Ubah kolom `husband_name`, `husband_nik`, dll menjadi **NULLABLE**.
    - ✅ Tambah kolom `category` (Enum: 'Umum', 'Bumil', 'Bayi/Balita', 'Lansia').
    - ✅ Tambah kolom `responsible_person` (nullable) untuk penanggung jawab.
    - **File:** `2026_02_01_180321_update_patients_table_add_general_columns.php`
    - **File:** `2026_02_02_095620_add_category_to_patients_table.php`

- **\[✅\] Buat Tabel general_visits (Poli Umum)**
    - ✅ Struktur SOAP lengkap (Subjective, Objective, Assessment, Plan).
    - ✅ Field: `complaint`, `systolic`, `diastolic`, `temperature`, `weight`, `height`, `physical_exam`, `diagnosis`, `icd10_code`, `therapy`, `status`, `payment_method`.
    - **File:** `2026_02_01_164508_create_general_visits_table.php`

## **🖥️ PHASE 2: NAVIGATION & INDEX (Tampilan Depan)** ✅ SELESAI

_Tujuan: Mengubah cara Admin/Bidan melihat daftar pasien agar tidak tercampur aduk antara Bumil dan Pasien Sakit Flu._

- **\[✅\] Refactor Sidebar Menu**
    - ✅ Ubah pengelompokan menu dengan group **"Layanan Medis"**.
    - ✅ Menu: Dashboard → Data Pasien → **\[Layanan Medis\]** → Pendaftaran, Poli Umum, Poli KIA, Poli KB, Poli Anak.
    - ✅ Tambah link ke `general-visits.index` untuk Poli Umum.
    - **File:** `resources/views/layouts/sidebar.blade.php`

- **\[✅\] Universal Patient Index (PatientList)**
    - ✅ Kolom universal: Nama, NIK, JK, Umur, Kategori, Alamat.
    - ✅ Filter berdasarkan `category` (Umum, Bumil, Bayi/Balita, Lansia).
    - ✅ Filter berdasarkan `gender` (Laki-laki, Perempuan).
    - ✅ Badge visual dengan icon untuk setiap kategori pasien.
    - ✅ Avatar berwarna (biru untuk laki-laki, pink untuk perempuan).
    - **File:** `app/Livewire/PatientList.php`
    - **File:** `resources/views/livewire/patient-list.blade.php`

- **\[✅\] General Visits Index (Daftar Kunjungan Poli Umum)**
    - ✅ List kunjungan dengan filter tanggal, status, dan pembayaran.
    - ✅ Statistik: Total kunjungan, hari ini, dirujuk, rawat inap.
    - **File:** `app/Livewire/GeneralVisitList.php`
    - **File:** `resources/views/livewire/general-visit-list.blade.php`
    - **Route:** `GET /general-visits` → `general-visits.index`

- **\[✅\] Auto-Update Category Logic**
    - ✅ Saat pasien pilih layanan di Registration Desk, `category` otomatis di-update:
        - **KIA/Nifas** → `Bumil`
        - **Poli Anak** → `Bayi/Balita`
        - **Poli Umum (usia ≥60)** → `Lansia`
        - **Poli Umum (usia <5)** → `Bayi/Balita`
    - **File:** `app/Livewire/PatientQueueEntry.php` (method `updatePatientCategory()`)

## **🏥 PHASE 3: THE UNIVERSAL PROFILE (Rekam Medis Terpadu)** ✅ SELESAI

_Tujuan: Satu halaman detail pasien yang bisa menampilkan SEMUA riwayat medis seseorang, apapun jenis layanannya._

- **\[✅\] Refactor PatientShow (Halaman Detail)**
    - ✅ **Header:** Identitas Pasien dengan avatar color-coded (biru/pink), demographics lengkap
    - ✅ **Body:** Tab system dengan Alpine.js (activeTab state)
    - ✅ **Tab Conditional Logic Implemented:**
        - **Tab 1: Riwayat Umum** (Default) → Mengambil data dari general_visits - SEMUA PASIEN
        - **Tab 2: Riwayat Kehamilan/ANC** → Hanya muncul jika `gender = 'P'`
        - **Tab 3: Riwayat Persalinan & Nifas** → Hanya muncul jika `gender = 'P'`
        - **Tab 4: Riwayat KB** → Hanya muncul jika `gender = 'P' AND age >= 15 AND age <= 49`
        - **Tab 5: Riwayat Imunisasi** → Hanya muncul jika `age < 5`
    - ✅ **Implementation Details:**
        - Controller: PatientController::show() dengan eager loading semua relationships
        - View: resources/views/patients/show.blade.php (modular dengan 5 partial views)
        - Partials: patients/tabs/{general, anc, delivery, kb, immunization}.blade.php
        - Empty states dengan CTA buttons untuk setiap tab
        - Backup original: show-old-backup.blade.php (619 lines)

## **🚪 PHASE 4: THE GATEKEEPER (Alur Pendaftaran)** ✅ SUDAH DIKERJAKAN SEBELUMNYA

_Tujuan: Menangani kasus "Pasien Pindahan" dan mengarahkan pasien ke "Kamar" yang benar._

- **\[✅\] Halaman "Pendaftaran Kunjungan" (ServiceRegistration)**
    - ✅ Konsep "Meja Resepsionis" sudah implementasi.
    - ✅ Alur: Cari Pasien → Pilih Pasien → Pilih Layanan.
    - ✅ 5 Pilihan: Poli Umum, Poli KIA, Poli KB, Poli Anak, Poli Nifas.
    - **File:** `app/Livewire/PatientQueueEntry.php`
    - **Route:** `GET /registration-desk`

- **\[✅\] Flexible Entry Logic (Fitur Pindahan)**
    - ✅ Kasus pasien pindahan (melahirkan di RS lain) sudah ditangani.
    - ✅ Modal warning untuk Nifas tanpa data persalinan.
    - ✅ Auto-create placeholder pregnancy dengan notes "Data persalinan transfer dari luar".
    - **File:** `app/Livewire/PatientQueueEntry.php` (method `proceedToNifas()`)
- **\[ \] Flexible Entry Logic (Fitur Pindahan)**
    - **Kasus:** Ibu habis melahirkan di RS lain, datang ke klinik cuma mau kontrol jahitan (Nifas). Data kehamilannya tidak ada di sistem kita.
    - **Logic:**
        - Jika Bidan pilih "Poli Nifas" TAPI data pregnancies kosong/tidak aktif.
        - **JANGAN ERROR/BLOKIR.**
        - **Action:** Tampilkan Modal _"Pasien ini tidak punya data hamil aktif. Apakah ini pasien pindahan?"_.
        - **Result:** Jika Ya, minta input cepat (Tanggal Lahir Bayi & Penolong), simpan sebagai _History_, lalu izinkan masuk form Nifas.
