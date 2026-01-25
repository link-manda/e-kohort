# **MODUL BARU: GIZI & TUMBUH KEMBANG (DIGITAL KMS)**

**Context:**  
Kita akan membangun modul pemantauan pertumbuhan anak. Modul ini bertujuan menggantikan KMS (Kartu Menuju Sehat) fisik menjadi digital.  
**Core Value:** Deteksi dini Stunting (Pendek), Wasting (Kurus), dan Underweight (Gizi Buruk) secara otomatis tanpa kalkulator manual.  
**Input:** Data Anak (dari modul Imunisasi).  
**Output:** Grafik Pertumbuhan, Status Gizi (Z-Score), dan Laporan PMT (Pemberian Makanan Tambahan).

## **1\. DATABASE SCHEMA (Migrations)**

Agent harus membuat migration untuk menampung data pengukuran bulanan dan tabel referensi standar WHO.

### **A. Tabel child\_growth\_records (Transaksi Bulanan)**

* id (BigInt)  
* child\_id (FK \-\> children).  
* record\_date (Date \- Tanggal Timbang).  
* age\_in\_months (Integer \- Usia saat timbang, kalkulasi otomatis).  
* **Anthropometry (Input):**  
  * weight (Float \- Berat Badan kg).  
  * height (Float \- Tinggi/Panjang Badan cm).  
  * head\_circumference (Float \- Lingkar Kepala cm, Optional).  
  * measurement\_method (Enum: 'Terlentang/Recumbent', 'Berdiri/Standing') \-\> *Penting untuk koreksi tinggi badan pada anak usia 2 tahun*.  
* **Status Gizi (Output Kalkulasi \- Disimpan agar query report cepat):**  
  * zscore\_bb\_u (Float) \-\> *BB per Umur (Gizi Buruk/Baik)*.  
  * status\_bb\_u (Enum: 'Gizi Buruk', 'Kurang', 'Baik', 'Lebih').  
  * zscore\_tb\_u (Float) \-\> *TB per Umur (Stunting)*.  
  * status\_tb\_u (Enum: 'Sangat Pendek', 'Pendek', 'Normal', 'Tinggi').  
  * zscore\_bb\_tb (Float) \-\> *BB per TB (Wasting)*.  
  * status\_bb\_tb (Enum: 'Gizi Buruk', 'Gizi Kurang', 'Baik', 'Gizi Lebih', 'Obesitas').  
* **Intervensi:**  
  * vitamin\_a (Enum: 'Tidak', 'Biru (6-11 bln)', 'Merah (1-5 thn)').  
  * deworming\_medicine (Boolean \- Obat Cacing).  
  * pmt\_given (Boolean \- Pemberian Makanan Tambahan).  
* notes (Text).  
* midwife\_name (String).

### **B. Tabel who\_standards (Tabel Master Standar WHO)**

*Jangan hardcode logic WHO di PHP, simpan di database agar performa tinggi.*

* id (BigInt)  
* gender (Enum: 'L', 'P').  
* type (Enum: 'BB\_U', 'TB\_U', 'BB\_TB').  
* age\_month (Integer, Nullable).  
* length\_cm (Float, Nullable \- Khusus untuk tabel BB/TB).  
* sd\_minus\_3 (Float).  
* sd\_minus\_2 (Float).  
* sd\_minus\_1 (Float).  
* sd\_median (Float).  
* sd\_plus\_1 (Float).  
* sd\_plus\_2 (Float).  
* sd\_plus\_3 (Float).

## **2\. BUSINESS LOGIC (Calculation Service)**

Buat GrowthCalculatorService dengan logika berikut:

1. **Age Calculation:** Hitung selisih bulan antara dob (Date of Birth) dan record\_date.  
2. **Height Correction:**  
   * Jika anak diukur **Terlentang** tapi umur \>= 2 tahun: Kurangi TB sebesar 0.7 cm.  
   * Jika anak diukur **Berdiri** tapi umur \< 2 tahun: Tambah TB sebesar 0.7 cm.  
3. **Z-Score Algorithm (Interpolasi Linear):**  
   * Ambil nilai referensi dari tabel who\_standards berdasarkan Gender dan Umur.  
   * Bandingkan nilai input anak dengan nilai Median dan Standar Deviasi (SD).  
   * Rumus Z-Score: (Nilai Anak \- Nilai Median) / Standar Deviasi Reference.  
   * Tentukan kategori (misal: Z-Score \< \-2 SD \= Stunting).

*Note untuk Agent: Untuk tahap awal, buatkan Seeder sampel WHO Standards (hanya beberapa bulan pertama) agar logic bisa ditest. Jangan minta saya input ribuan baris WHO manual.*

## **3\. FRONTEND UI (Livewire: GrowthEntry)**

### **A. Layout "KMS Digital"**

1. **Header:** Identitas Anak & Usia (Format: X Tahun Y Bulan).  
2. **Input Form (Kiri):**  
   * Tanggal, BB, TB, Cara Ukur.  
   * **Action:** Saat BB/TB diketik, jalankan *Real-time Calculation*.  
3. **Status Badges (Kanan):**  
   * Tampilkan 3 Kotak Indikator (BB/U, TB/U, BB/TB).  
   * Warna otomatis berubah:  
     * Hijau (Normal)  
     * Kuning (Kurang/Pendek)  
     * Merah (Buruk/Sangat Pendek/Stunting).  
4. **Grafik Pertumbuhan (Bawah):**  
   * Gunakan library chart (misal: ApexCharts).  
   * Tampilkan **Grafik Garis (Line Chart)**.  
   * Sumbu X: Umur (Bulan).  
   * Sumbu Y: Berat Badan.  
   * **Background Zones:** Tampilkan area pita warna hijau/kuning/merah ala KMS (bisa menggunakan *Annotation* atau *Multiple Series* chart).

## **4\. REPORTING (Intervensi Stunting)**

Fitur Export Excel untuk Dinas Kesehatan:

* **Filter:** Bulan/Tahun.  
* **Logic Filter:** "Tampilkan hanya anak yang Stunting (TB/U \< \-2 SD)".  
* **Output:** Nama Anak, Nama Ortu, Alamat, Nilai Z-Score. (Data ini dipakai untuk kirim bantuan PMT).