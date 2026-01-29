# **TASK: CREATE EXCEL EXPORT FOR DELIVERY REGISTER (REGISTER PERSALINAN)**

**Context:**  
Kita membutuhkan fitur Export Excel untuk data Persalinan.  
Format laporan ini meniru "Buku Register Persalinan Bidan" (Template Bu Yanti).  
Struktur tabelnya memiliki **Header Bertingkat (Nested Headers)**.  
**Target:**

1. Class Export: App\\Exports\\DeliveryRegisterExport.  
2. View Blade: resources/views/exports/delivery\_register.blade.php.

## **1\. VISUAL STRUCTURE (HTML TABLE DESIGN)**

Agent harus membuat View Blade dengan struktur HTML \<table\> yang menggunakan rowspan dan colspan untuk meniru layout berikut:  
**Analisis Header (2 Baris):**

* **Baris 1 (Parent Headers):**  
  * NO (Rowspan 2\)  
  * TANGGAL (Rowspan 2\)  
  * NO KK (Rowspan 2\)  
  * **IBU** (Colspan 5: Nama, NIK, TTL, Umur, HP) \-\> *Header Group*  
  * **SUAMI** (Colspan 5: Nama, NIK, TTL, Umur, HP) \-\> *Header Group*  
  * ALAMAT (Rowspan 2\)  
  * TGL/JAM BERSALIN (Rowspan 2\)  
  * JENIS PERSALINAN (Rowspan 2\)  
  * PENYULIT (Rowspan 2\)  
  * EPISIOTOMI (Rowspan 2\)  
  * **MANAJEMEN AKTIF KALA 3** (Colspan 3: Oksitosin, Peregangan, Masase) \-\> *Header Group*  
  * PEMANTAUAN 2 JAM PP (Rowspan 2\)  
  * KETERANGAN (Rowspan 2\)  
* **Baris 2 (Child Headers):**  
  * *(Di bawah IBU)*: NAMA, NIK, TTL, UMUR, HP  
  * *(Di bawah SUAMI)*: NAMA, NIK, TTL, UMUR, HP  
  * *(Di bawah MANAJEMEN KALA 3\)*: Suntik Oksitosin, PTT/Penjahitan, Masase Uterus

## **2\. DATA MAPPING LOGIC**

**Data Source:**  
Gunakan Model DeliveryRecord (yang berelasi dengan Pregnancy dan Patient).  
*Jika DeliveryRecord belum ada, gunakan Pregnancy dengan status 'Lahir'.*  
**Kolom Mapping:**

1. **IBU:**  
   * Nama: $delivery-\>pregnancy-\>patient-\>name  
   * NIK: $delivery-\>pregnancy-\>patient-\>nik  
   * TTL: Gabungan Tempat \+ Tgl Lahir  
   * Umur: Hitung umur saat persalinan (Carbon diff).  
2. **SUAMI:**  
   * Ambil dari data suami di tabel patients (husband\_name, husband\_nik, dll).  
3. **PERSALINAN:**  
   * Tgl/Jam: $delivery-\>delivery\_date\_time  
   * Jenis: $delivery-\>delivery\_method (Normal/Sungsang/dll)  
   * Penyulit: $delivery-\>complications atau $delivery-\>pregnancy-\>risk\_notes.  
   * Episiotomi: $delivery-\>perineum\_rupture (Cek apakah Episiotomi/Robek).  
4. **MANAJEMEN KALA 3 (Checklist):**  
   * *Logic:* Jika data di database (boolean) \= true, tampilkan "Ya" atau "v". Jika false, "Tidak".  
   * Oksitosin: Cek $delivery-\>oxytocin\_injection (perlu ditambahkan di migrasi jika belum ada).  
   * Penjahitan: Cek $delivery-\>perineum\_suturing.

## **3\. TECHNICAL IMPLEMENTATION STEPS**

1. **Create Export Class:**  
   * Gunakan FromView, ShouldAutoSize, WithStyles.  
   * Constructor menerima filter $month dan $year.  
   * Query data menggunakan Eager Loading (with('pregnancy.patient')) agar performa cepat.  
2. **Create Blade View:**  
   * Gunakan styling inline sederhana untuk border: style="border: 1px solid black; vertical-align: middle; text-align: center;".  
   * Pastikan \<thead\> rapi sesuai struktur Visual Structure di atas.  
3. **Styling:**  
   * Set seluruh tabel agar teks-nya **Wrap Text** (agar alamat panjang tidak melebar).  
   * Header harus **Bold** dan **Center**.

## **4\. OUTPUT EXPECTATION**

Hasilkan kode lengkap untuk DeliveryRegisterExport.php dan delivery\_register.blade.php.  
Pastikan Agent memahami bahwa "SUAMI" dan "IBU" adalah kolom yang memayungi sub-kolom di bawahnya.