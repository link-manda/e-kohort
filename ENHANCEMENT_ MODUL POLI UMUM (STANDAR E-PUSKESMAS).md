# **ENHANCEMENT: MODUL POLI UMUM (STANDAR E-PUSKESMAS)**

**Context:**  
Berdasarkan benchmarking dengan E-Puskesmas, modul general\_visits (Poli Umum) kita masih terlalu sederhana. Kita perlu upgrade agar mencakup **Skrining PTM (Penyakit Tidak Menular)** dan **Pemeriksaan Fisik Head-to-Toe**.  
**Referensi:** *Export speciment Pelayanan Umum.pdf* (E-Puskesmas Denpasar).

## **1\. DATABASE REFACTORING (general\_visits)**

Agent harus membuat migration baru add\_details\_to\_general\_visits\_table.

### **A. Tambahan Kolom Anamnesa & Vital Sign**

* consciousness (Enum: 'Compos Mentis', 'Somnolen', 'Sopor', 'Koma') \-\> *Kesadaran*.  
* is\_emergency (Boolean) \-\> *Status Gawat Darurat*.  
* waist\_circumference (Float) \-\> *Lingkar Perut (Screening Obesitas Sentral)*.  
* bmi (Float) \-\> *IMT (Hitung otomatis dari BB/TB)*.  
* respiratory\_rate (Integer) \-\> *RR (x/menit)*.  
* heart\_rate (Integer) \-\> *Nadi (x/menit)*.

### **B. Kolom Gaya Hidup (Skrining PTM)**

* lifestyle\_smoking (Enum: 'Tidak', 'Ya', 'Jarang').  
* lifestyle\_alcohol (Boolean).  
* lifestyle\_activity (Enum: 'Aktif', 'Kurang Olahraga').  
* lifestyle\_diet (Enum: 'Sehat', 'Kurang Sayur/Buah', 'Tinggi Gula/Garam/Lemak').

### **C. Riwayat Kesehatan (Penting untuk Alergi Obat)**

* allergies (Text/JSON) \-\> *Riwayat Alergi*.  
* medical\_history (Text) \-\> *Riwayat Penyakit Dahulu*.

### **D. Pemeriksaan Fisik Head-to-Toe (JSON Column)**

*Jangan buat puluhan kolom.* Buat satu kolom **physical\_assessment\_details (JSON)** untuk menyimpan checklist:

* Struktur JSON yang diharapkan:  
  {  
    "kepala": "Normal",  
    "mata": "Anemis (-), Ikterik (-)",  
    "telinga": "Normal",  
    "leher": "Tidak ada pembesaran kelenjar",  
    "thorax\_jantung": "Normal",  
    "thorax\_paru": "Vesikuler",  
    "abdomen": "Supel, Bising usus (+)",  
    "ekstremitas": "Akral hangat",  
    "genitalia": "Tidak diperiksa"  
  }

## **2\. UI/UX: LAYOUT FORMULIR (GeneralVisitEntry)**

Jangan gunakan satu halaman panjang ke bawah. Gunakan **TABS / ACCORDION Layout** agar rapi seperti E-Puskesmas.

### **Tab 1: Subjective (Anamnesa)**

* Input Keluhan Utama.  
* **Card Riwayat:** Input Alergi (Alert Merah jika ada), Riwayat Penyakit.  
* **Card Gaya Hidup:** Radio Button untuk Rokok, Alkohol, Olahraga (Layout Grid).

### **Tab 2: Objective (Pemeriksaan)**

* **Vital Sign Bar:** Input Tensi, Nadi, Suhu, Nafas, BB, TB, Lingkar Perut.  
  * *Auto-Calculate:* IMT langsung muncul angka & kategorinya (Kurus/Normal/Gemuk).  
* **Head to Toe Checklist:**  
  * Tampilkan list (Kepala, Mata, Leher, dll).  
  * Default value: "Normal/Tidak Ada Kelainan".  
  * User bisa klik untuk edit jika ada kelainan (misal: "Mata: Konjungtiva Anemis").

### **Tab 3: Assessment & Plan (Diagnosa & Obat)**

* **Diagnosa:** Gunakan component Select2 ICD-10 yang sudah ada.  
* **Resep Obat (Repeater):**  
  * Tombol "+ Tambah Obat".  
  * Row Input: \[Nama Obat\] \- \[Jumlah\] \- \[Aturan Pakai/Signa (misal 3x1 sesudah makan)\].  
  * Simpan data obat ini di tabel relasi baru prescriptions atau JSON di general\_visits (pilih relasi hasMany ke tabel prescriptions agar stok obat bisa dikelola nanti).

## **3\. PDF REPORT (Output)**

Update fitur Export/Print PDF agar layoutnya meniru *Export speciment Pelayanan Umum.pdf*:

* Header: Logo Pemkot/Dinas.  
* Layout 2 Kolom: Kiri (Anamnesa), Kanan (Fisik).  
* Footer: Diagnosa & Obat.