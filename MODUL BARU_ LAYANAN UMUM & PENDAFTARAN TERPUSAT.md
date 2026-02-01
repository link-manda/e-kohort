# **MODUL BARU: LAYANAN UMUM & PENDAFTARAN TERPUSAT**

**Context:**  
Klien meminta sistem dikembangkan menjadi Klinik Pratama/Puskesmas. Artinya, pasien tidak hanya Ibu Hamil. Ada pasien umum (Pria/Wanita/Anak) yang datang untuk berobat sakit biasa (Poli Umum).  
Sistem juga harus fleksibel: Ibu yang datang bisa langsung minta layanan Nifas/KB tanpa harus terdaftar hamil dulu di sistem ini (pindahan dari klinik lain).  
**Goal:** Membuat tabel kunjungan umum dan fitur "Pendaftaran Harian" yang berfungsi sebagai router ke berbagai modul (ANC, Umum, KB, Anak).

## **1\. DATABASE UPDATE**

Agent harus melakukan migrasi berikut:

### **A. Update Tabel patients (Generalisasi)**

* Tambahkan kolom gender (Enum: 'L', 'P'). Default 'P' untuk data lama.  
* Pastikan kolom-kolom spesifik suami (husband\_name, dll) bersifat **Nullable**.  
* Tambahkan kolom responsible\_person (String) \-\> Penanggung Jawab (bisa Suami/Istri/Ayah).

### **B. Buat Tabel general\_visits (Poli Umum)**

Tabel ini untuk mencatat pengobatan umum (Sakit Kepala, Luka, Demam, dll).

* id (BigInt)  
* patient\_id (FK \-\> patients).  
* visit\_date (DateTime).  
* complaint (Text) \-\> *Keluhan Utama (Subjective)*.  
* **Pemeriksaan Fisik (Objective):**  
  * systolic, diastolic (Tensi).  
  * temperature, weight, height.  
  * physical\_exam (Text) \-\> *Hasil periksa fisik*.  
* **Diagnosa (Assessment):**  
  * diagnosis (String).  
  * icd10\_code (String, nullable).  
* **Tindakan/Obat (Plan):**  
  * therapy (Text) \-\> *Resep obat/Tindakan*.  
* status (Enum: 'Pulang', 'Rujuk', 'Rawat Inap').  
* payment\_method (Enum: 'Umum', 'BPJS').

## **2\. FRONTEND UI: PENDAFTARAN (Registration Desk)**

Kita butuh komponen Livewire baru: PatientQueueEntry (Pendaftaran Kunjungan).  
**Flow Pendaftaran:**

1. **Cari Pasien:** Search by Nama/NIK/RM.  
   * *Jika belum ada:* Tombol "Buat Pasien Baru" (Form Identitas Umum).  
2. **Pilih Tujuan Layanan (Dropdown):**  
   * **Poli Umum** \-\> *Redirect ke Form GeneralVisit*.  
   * **Poli KIA (Ibu Hamil)** \-\> *Redirect ke Form AncRegister*.  
   * **Poli KB** \-\> *Redirect ke Form KbEntry*.  
   * **Poli Anak/Imunisasi** \-\> *Redirect ke Form ChildVisit*.  
   * **Poli Nifas** \-\> *Redirect ke Form PostnatalEntry*.

**Logic Khusus Nifas/KB (Flexible Entry):**  
Jika user memilih **Poli Nifas** tapi data pasien belum punya status "Lahir" di sistem (misal pasien pindahan):

* Sistem **JANGAN BLOKIR**.  
* Tampilkan Modal/Prompt: *"Pasien ini belum memiliki riwayat persalinan di sistem. Apakah ingin input data persalinan lalu (history) atau langsung ke Nifas?"*  
* Izinkan input Nifas dengan memberikan *warning* bahwa data persalinan kosong.

## **3\. UI POLI UMUM (GeneralVisitEntry)**

Buat form simple satu halaman untuk dokter/perawat umum:

* Header: Identitas Pasien.  
* Row 1: Tanda Vital (Tensi, Suhu, Nadi).  
* Row 2: Anamnesa & Pemeriksaan Fisik.  
* Row 3: Diagnosa & Terapi.  
* Tombol Simpan.

## **4\. REPORTING (Buku Register Umum)**

Fitur Export Excel untuk "Register Rawat Jalan":

* Kolom: No, Tgl, Nama, KK/Alamat, Umur, JK, Diagnosa, Terapi, Ket (Umum/BPJS).