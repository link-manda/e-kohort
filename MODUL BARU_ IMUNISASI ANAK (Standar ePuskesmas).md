# **MODUL BARU: IMUNISASI ANAK (Standar ePuskesmas)**

Context:  
Kita akan membangun modul Imunisasi Anak. Berdasarkan referensi PDF ePuskesmas, pencatatan imunisasi harus berbasis Kunjungan Medis (Medical Visit), bukan hanya checklist vaksin.

## **1\. Database Schema (Refactor & New Tables)**

Agent harus membuat migration untuk 3 tabel baru dengan relasi yang tepat.

### **A. Tabel children (Master Data Pasien Anak)**

* id (BigInt)  
* patient\_id (Foreign Key ke patients \- Ibunya).  
* nik (String 16, nullable \- Bayi baru lahir belum punya NIK).  
* no\_rm (String \- Nomor Rekam Medis Anak, misal: "CH-2024-001").  
* name (String).  
* gender (Enum: 'L', 'P').  
* dob (Date \- Tanggal Lahir).  
* pob (String \- Tempat Lahir).  
* birth\_weight (Float \- BB Lahir gram).  
* birth\_height (Float \- TB Lahir cm).  
* status (Enum: 'Hidup', 'Meninggal').

### **B. Tabel child\_visits (Kunjungan & Pemeriksaan Fisik)**

* id (BigInt)  
* child\_id (FK \-\> children).  
* visit\_date (DateTime \- Tgl & Jam Pendaftaran).  
* age\_month (Integer \- Usia bulan saat kunjungan).  
* **Anamnesa (Sesuai PDF):**  
  * complaint (Text \- Keluhan Utama).  
* **Pemeriksaan Fisik (Vital Signs \- Sesuai PDF):**  
  * weight (Float \- BB kg).  
  * height (Float \- TB cm).  
  * temperature (Float \- Suhu Celcius) \-\> **CRITICAL** (Demam \= Tunda Vaksin).  
  * heart\_rate (Integer \- Nadi).  
  * respiratory\_rate (Integer \- Nafas).  
  * head\_circumference (Float \- Lingkar Kepala).  
  * development\_notes (Text \- Catatan Tumbuh Kembang).  
* **Diagnosa:**  
  * icd\_code (String, nullable \- Misal "Z24.0").  
  * diagnosis\_name (String, nullable).

### **C. Tabel immunization\_actions (Tindakan Vaksinasi)**

* id (BigInt)  
* child\_visit\_id (FK \-\> child\_visits).  
* vaccine\_type (Enum/String: HB0, BCG, Polio 1-4, DPT-HB-Hib 1-3, IPV, MR).  
* batch\_number (String \- No Batch Vaksin).  
* body\_part (String \- Lokasi Suntikan: Paha Kanan/Kiri, Lengan).  
* provider\_name (String \- Nama Nakes).

## **2\. Models & Relations**

* **Model Patient (Ibu):** hasMany Children.  
* **Model Child:** belongsTo Patient (Ibu), hasMany ChildVisits.  
* **Model ChildVisit:** belongsTo Child, hasMany ImmunizationActions.

## **3\. Fitur Frontend (Livewire Components)**

### **A. Component: ChildRegistration**

* Form Input Bayi Baru.  
* **Fitur Penting:** Harus ada pencarian Nama Ibu / No WA Ibu. Bayi harus di-link ke Ibu yang sudah ada di database patients.

### **B. Component: ImmunizationEntry (Layout: Rekam Medis)**

* **Header:** Info Pasien (Nama, Umur, Nama Ibu).  
* **Step 1 \- Tanda Vital:** Input Suhu, BB, TB, Keluhan.  
* **Step 2 \- Diagnosa & Tindakan:**  
  * Input Kode ICD (Opsional).  
  * Pilih Vaksin (Bisa Multi-select atau Add Row jika suntik \> 1 vaksin sekaligus).  
* **History:** Tampilkan riwayat kunjungan sebelumnya di bawah form (Timeline).

## **4\. Business Logic**

* **Validasi Umur:** Saat memilih vaksin (misal Campak), berikan *warning* jika umur anak belum cukup (misal \< 9 bulan).  
* **Validasi Suhu:** Jika input temperature \> 37.5, tampilkan *Alert* "Anak Demam, pertimbangkan tunda imunisasi".