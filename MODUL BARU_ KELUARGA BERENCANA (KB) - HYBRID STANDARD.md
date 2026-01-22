# **MODUL BARU: KELUARGA BERENCANA (KB) \- HYBRID STANDARD**

Context:  
Kita akan membangun modul KB yang menggabungkan "Kemudahan Input" (User Friendly) sesuai Template Klien, namun memiliki "Kedalaman Data Medis" sesuai standar ePuskesmas.  
**Referensi Dokumen:**

1. *Template KB (Bu Yanti).xlsx*: Menentukan varian metode kontrasepsi.  
2. *PrintOut-Contoh Data KB.pdf*: Menentukan standar pemeriksaan fisik dan diagnosa.  
3. *Laporan Harian.xlsx*: Menentukan kebutuhan filter laporan (Baru/Lama).

## **1\. DATABASE SCHEMA (Refactor & New Tables)**

Agent harus membuat migration untuk tabel kb\_visits dengan struktur gabungan berikut:

### **A. Tabel kb\_visits (Transaksi Kunjungan)**

* id (BigInt)  
* patient\_id (FK \-\> patients).  
* visit\_date (DateTime \- Tgl & Jam).  
* no\_rm (String \- Diambil dari Patient, tapi simpan snapshot jika perlu).  
* **Status Kunjungan (Laporan Requirement):**  
  * visit\_type (Enum: 'Peserta Baru', 'Peserta Lama', 'Ganti Cara').  
  * payment\_type (Enum: 'Umum', 'BPJS').  
* **Pemeriksaan Fisik (ePuskesmas Standard):**  
  * weight (Float \- BB kg).  
  * blood\_pressure\_systolic (Int).  
  * blood\_pressure\_diastolic (Int).  
  * physical\_exam\_notes (Text) \-\> *Untuk mencatat hasil periksa dalam (Genitalia/Vagina) jika pasang IUD*.  
* **Metode Kontrasepsi (Template Bu Yanti Spec):**  
  * contraception\_method (String/Enum). *Lihat Section Seeder*.  
  * contraception\_brand (String, nullable) \-\> *Misal: Triclofem, Cyclofem*.  
* **Tindakan & Medis:**  
  * icd\_code (String, nullable) \-\> *Misal: Z30.5*.  
  * diagnosis (String, nullable).  
  * side\_effects (Text, nullable) \-\> *Keluhan/Efek Samping*.  
  * complications (Text, nullable).  
  * informed\_consent (Boolean) \-\> *Wajib True untuk tindakan invasif (IUD/Implant)*.  
* **Penjadwalan:**  
  * next\_visit\_date (Date) \-\> *Critical: Hasil kalkulasi otomatis*.  
* midwife\_name (String).

## **2\. MASTER DATA (Seeder Logic)**

Template Bu Yanti sangat spesifik soal jenis alat. Buat KbMethodSeeder atau Enum yang mencakup:  
**Kategori & Varian:**

1. **SUNTIK:**  
   * Suntik 1 Bulan (Cyclofem)  
   * Suntik 3 Bulan (Depo Progestin)  
2. **PIL:**  
   * Pil Kombinasi  
   * Pil Laktasi (Menyusui)  
3. **IMPLANT (Susuk):**  
   * Implant 1 Batang  
   * Implant 2 Batang  
4. **IUD (Spiral):**  
   * IUD CuT 380A  
   * IUD Nova T  
   * IUD Silverline (Sesuai request Bu Yanti)  
5. **LAINNYA:**  
   * Kondom  
   * MOW (Steril Wanita) / MOP (Steril Pria)

## **3\. BUSINESS LOGIC (Auto-Schedule Service)**

Buat logic di Backend (KbSchedulingService) untuk menghitung next\_visit\_date:

* **Logic Suntik 1 Bulan:** visit\_date \+ 28 Hari.  
* **Logic Suntik 3 Bulan:** visit\_date \+ 12 Minggu (84 Hari).  
* **Logic Pil:** visit\_date \+ 1 Bulan (saat pil habis).  
* **Logic IUD/Implant (Pasang Baru):** visit\_date \+ 1 Minggu (Kontrol Pasca Pasang).  
* **Logic IUD/Implant (Kontrol Rutin):** visit\_date \+ 1 Tahun.

## **4\. FRONTEND UI (Livewire: KbEntry)**

Desain form harus "Clean" tapi "Lengkap". Gunakan **Reactive Form** (Tampilan berubah sesuai metode yang dipilih).

### **Section 1: Header & Screening**

* Identitas Ibu (Auto load dari database).  
* **Tensi & BB (Critical):**  
  * Jika Tensi \>= 140/90: Disable pilihan "Suntik" dan "Pil Kombinasi". Tampilkan Alert Merah: *"Hipertensi\! Sarankan IUD atau Implant non-hormonal"* (Standard WHO MEC).

### **Section 2: Pemilihan Metode (The Core)**

* Dropdown: "Jenis Kunjungan" (Baru/Lama).  
* Cards/Radio Button: Pilih Metode (Suntik/Pil/IUD/Implant).  
  * *Interactive:* Jika pilih IUD, muncul dropdown tambahan: "Jenis IUD" (Copper T/Silverline).  
  * *Interactive:* Jika pilih Implant, muncul dropdown: "Jumlah Batang" (1/2).

### **Section 3: Tindakan & Consent**

* **Checkbox:** ☑ "Informed Consent ditandatangani".  
* **Input Diagnosa (ICD-10):** Autocomplete (Z30.0, Z30.5, dll).  
* **Result:** Field Tanggal Kembali otomatis terisi (Readonly tapi bisa di-override).

## **5\. REPORTING REQUIREMENT (Laporan Harian.xlsx)**

Sistem harus bisa menjawab pertanyaan laporan ini nanti:

* "Berapa peserta **Baru** bulan ini?"  
* "Berapa peserta **Lama** bulan ini?"  
* "Berapa yang pakai **BPJS** vs Umum?"  
* "Berapa akseptor **IUD Silverline**?"

Maka dari itu, pastikan kolom visit\_type, payment\_type, dan contraception\_method tersimpan dengan rapi dan konsisten.