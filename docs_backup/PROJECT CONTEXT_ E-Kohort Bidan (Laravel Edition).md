# **PROJECT CONTEXT: E-Kohort Bidan (Laravel Edition)**

## **1\. Project Overview**

Aplikasi ini adalah digitalisasi "Register Kohort Ibu Hamil" untuk Bidan Desa. Menggantikan Excel dengan Web App yang responsif.  
Core Philosophy: "Continuity of Care" (Satu pasien, banyak siklus kehidupan: Hamil \-\> Nifas \-\> KB).

## **2\. Tech Stack & Environment (REVAMPED)**

* **Framework:** Laravel 12 (atau versi stabil terbaru).  
* **Language:** PHP 8.2+.  
* **Database:** MySQL.  
* **Frontend:** Blade Templates \+ Livewire (untuk interaktivitas tanpa ribet JS) \+ Tailwind CSS.  
* **Icons:** Lucide Icons / Heroicons.

## **3\. Database Schema (MySQL \- Relational)**

Agent harus merancang Migrations dengan relasi Foreign Key yang ketat (onDelete('cascade')):

### **A. Tabel Utama**

1. **users** (Bidan/Petugas).  
2. **patients** (Data Ibu \- Master Data).  
   * Columns: id, nik (unique), name, dob, address, phone, blood\_type, husband\_name, husband\_nik.  
3. **pregnancies** (Siklus Kehamilan \- One-to-Many dari Patients).  
   * Columns: id, patient\_id (FK), gravida (G/P/A), hpht, edd (HPL), status (Active/Delivered/Aborted).  
4. **anc\_visits** (Kunjungan Bulanan \- One-to-Many dari Pregnancies).  
   * Columns: id, pregnancy\_id (FK), visit\_date, trimester, gestational\_age, weight, blood\_pressure\_systolic, blood\_pressure\_diastolic, map\_score (float), risk\_status (High/Low).

### **B. Tabel Penunjang (Sesuai Register Excel)**

5. **lab\_results** (One-to-One dengan anc\_visits).  
   * Columns: hb, protein\_urine, hiv\_status, syphilis\_status, hbsag\_status.  
6. **postpartum\_cares** (Nifas/KF \- Linked ke pregnancies).  
7. **family\_planning\_records** (KB \- Linked ke patients).

## **4\. Medical Business Logic (Sama seperti sebelumnya)**

1. **MAP Calculator (Wajib di Controller/Livewire Component):**  
   * $map \= $diastole \+ (1/3 \* ($systole \- $diastole));  
   * Logic Alert: Merah jika \> 100, Kuning jika \> 90\.  
2. **Triple Eliminasi:**  
   * Jika kolom hiv\_status, syphilis\_status, atau hbsag\_status berisi 'Reaktif', flag risk\_status jadi 'High'.

## **5\. Development Phases (Laravel Workflow)**

### **Phase 1: Foundation (Migration & Auth)**

* Setup Laravel Project \+ Tailwind.  
* **Migrations:** Buat tabel patients, pregnancies, anc\_visits.  
* **Models:** Setup Eloquent Relationships (Patient hasMany Pregnancy, Pregnancy hasMany AncVisit).

### **Phase 2: CRUD & Wizard Input**

* **Livewire Form:** Buat form "Multi-step Wizard" untuk input ANC agar tidak reload halaman.  
* **Validasi:** Gunakan Laravel Request Validation untuk memastikan NIK 16 digit, dll.

### **Phase 3: Medical Logic**

* Implementasi kalkulasi MAP otomatis di sisi Backend (sebelum simpan) atau Livewire (realtime update).

## **6\. Coding Guidelines for Agent**

* **Naming:** Gunakan snake\_case untuk database columns, camelCase untuk variabel PHP.  
* **Security:** Gunakan Mass Assignment Protection ($fillable).  
* **UI:** Gunakan Tailwind Utility classes. Pastikan Mobile Responsive.