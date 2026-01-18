# **REVISI MODUL IMUNISASI (Sesuai Template Excel User)**

Context:  
Berdasarkan "Template Excel Imunisasi.xlsx" dari klien, kita perlu melakukan update pada struktur database dan form input yang sebelumnya dirancang (V3). Klien membutuhkan pencatatan obat (Paracetamol), jenis vaksin baru (PCV/Rota), dan status informed consent.

## **1\. Database Migration Update**

Agent harus membuat migration baru untuk memodifikasi tabel child\_visits dan immunization\_actions (atau buat tabel referensi baru).

### **A. Update Tabel child\_visits**

Tambahkan kolom-kolom berikut:

* nutritional\_status (Enum: 'Gizi Buruk', 'Gizi Kurang', 'Gizi Baik', 'Gizi Lebih', 'Obesitas') \-\> *Untuk kolom Status Gizi*.  
* informed\_consent (Boolean) \-\> *Untuk checklist persetujuan tindakan*.  
* medicine\_given (String/Enum: 'Parasetamol Drop', 'Parasetamol Sirup', 'Tidak Ada', 'Lainnya').  
* medicine\_dosage (String, nullable) \-\> *Contoh: "3x0.5 ml"*.  
* notes (Text, nullable) \-\> *Kolom Keterangan*.

### **B. Update Master Data Vaksin (Seeder/Enum)**

Update daftar vaccine\_type agar mendukung vaksin modern (Program Pemerintah Baru):

* **Hexavalen 1, 2, 3** (Menggantikan istilah DPT-HB-Hib 1, 2, 3).  
* **PCV 1, 2, 3** (Pneumococcal Conjugate Vaccine).  
* **Rota 1, 2, 3** (Rotavirus).  
* **IPV 1, 2**.  
* **MR 1, 2** (Measles Rubella).

## **2\. Update Form Input (Livewire: ImmunizationEntry)**

Refactor tampilan form agar sesuai alur Excel:

### **Section 1: Data & Consent (Atas)**

* Tampilkan Identitas Anak & Ibu (NIK Ibu wajib muncul).  
* **NEW:** Checkbox Besar: ☑ **"Informed Consent (Persetujuan Tindakan Medis)"**. (Wajib dicentang sebelum simpan).

### **Section 2: Fisik & Gizi**

* Input BB, TB, Lingkar Kepala.  
* **NEW:** Dropdown **Status Gizi** (Biarkan bidan memilih manual dulu berdasarkan KMS, atau auto-calculate jika memungkinkan).

### **Section 3: Vaksin & Obat**

* Pilihan Vaksin (Gunakan nama baru: Hexavalen, PCV, Rota).  
* **NEW:** Section **"Pemberian Obat / KIPI"**:  
  * Toggle: "Berikan Obat Panas?" (Ya/Tidak).  
  * Jika Ya: Muncul Dropdown Jenis Obat (Parasetamol Drop/Sirup) & Input Dosis.

## **3\. Fitur Export Excel (Requirement Khusus)**

Buatkan 2 Class Export terpisah (Maatwebsite\\Excel):

**A. MonthlyImmunizationExport**

* Filter: Bulan & Tahun.  
* Output: Daftar semua anak yang imunisasi di bulan tersebut.  
* Kolom: Sesuai Header Excel Template (Nama, NIK, Ortu, BB, Vaksin yg disuntik, Obat).

**B. IndividualImmunizationExport**

* Filter: ID Anak (child\_id).  
* Output: Riwayat lengkap imunisasi anak tersebut dari lahir sampai sekarang (Row \= Tanggal Kunjungan).