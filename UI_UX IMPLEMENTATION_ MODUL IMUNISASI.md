# **UI/UX IMPLEMENTATION: MODUL IMUNISASI**

Context:  
Backend (Tabel & Model) sudah siap. Sekarang Agent harus membuat Tampilan Frontend (Blade Views & Livewire) dan Navigasi.  
**Design System:**

* Framework: Tailwind CSS.  
* Icons: Lucide Icons (Gunakan icon Baby, Syringe, Thermometer, Ruler).  
* Style: Bersih, Card-based, Mobile-responsive.

## **1\. Routing & Navigation (Menu)**

A. Route Definition (routes/web.php)  
Tambahkan route group baru untuk Imunisasi:

* GET /imunisasi \-\> App\\Livewire\\ChildIndex (Daftar Anak).  
* GET /imunisasi/daftar \-\> App\\Livewire\\ChildRegistration (Form Anak Baru).  
* GET /imunisasi/{childId}/kunjungan \-\> App\\Livewire\\ImmunizationEntry (Input Kunjungan/Suntik).

B. Sidebar Menu (navigation.blade.php atau sidebar.blade.php)  
Tambahkan menu item baru di bawah "ANC" atau "Ibu Hamil":

* Label: "Imunisasi Anak"  
* Icon: Baby (Bayi)  
* Active State: Saat berada di route imunisasi.\*.

## **2\. Component: Daftar Pasien Anak (ChildIndex)**

Buatkan view Livewire dengan fitur:

1. **Header:** Judul "Data Pasien Anak" dan Tombol "Tambah Bayi Baru" (Primary Color).  
2. **Search Bar:** Input pencarian besar.  
   * *Logic:* Cari berdasarkan Nama Anak, No RM, atau Nama Ibu.  
3. **Data Table / Card List (Mobile):**  
   * Tampilkan: Nama Anak, Jenis Kelamin (Icon L/P), Tgl Lahir, **Usia Saat Ini** (Gunakan helper age calculator), Nama Ibu.  
   * **Action Button:** "Buka Rekam Medis" (Link ke ImmunizationEntry).

## **3\. Component: Pendaftaran Bayi (ChildRegistration)**

Buatkan form wizard sederhana:

1. **Step 1: Link ke Ibu (CRITICAL)**  
   * Tidak boleh input manual nama ibu.  
   * Gunakan **Searchable Dropdown / Modal** untuk mencari data Ibu dari tabel patients.  
   * Tampilkan: "Cari Nama Ibu / No WA".  
2. **Step 2: Data Bayi**  
   * Nama, JK, Tgl Lahir, BB Lahir, TB Lahir.  
   * **Auto-Generate No RM:** Tampilkan field No RM sebagai *readonly* (terisi otomatis oleh logic backend).

## **4\. Component: Input Kunjungan & Imunisasi (ImmunizationEntry)**

Ini adalah halaman utama kerja Bidan. Layout harus seperti **Medical Chart**.

**Layout Structure:**

**A. Patient Header (Sticky Top)**

* Kartu informasi singkat: Nama Anak, No RM, **Usia Persis (X Thn Y Bln Z Hari)**, Nama Ibu.  
* Badge Status: "Sehat" atau "Meninggal".

**B. Section 1: Anamnesa & Vital Sign (Grid Layout)**

* Judul: "Pemeriksaan Fisik".  
* Input Fields (dibuat compact):  
  * **Suhu (Temp):** Input angka. **Alert UI:** Jika \> 37.5, border jadi merah & muncul teks "DEMAM \- Tunda Vaksin".  
  * **BB (kg) & TB (cm).**  
  * **Keluhan:** Textarea pendek.

**C. Section 2: Tindakan Imunisasi (Dynamic Table)**

* Judul: "Pemberian Vaksin".  
* **Multi-row Input:** Karena 1 kunjungan bisa 2 suntikan (misal DPT 1 \+ Polio 2).  
* Kolom:  
  1. **Jenis Vaksin:** Dropdown (ambil dari VaccineSeeder).  
  2. **No Batch:** Text input.  
  3. **Lokasi Suntik:** Dropdown (Paha Kiri, Paha Kanan, Lengan, Mulut).  
* Tombol: "+ Tambah Vaksin Lain".

**D. Section 3: Diagnosa (ICD-10)**

* Searchable Select untuk memilih diagnosa (misal "Z24.0").

**E. History Timeline (Bawah)**

* Tampilkan riwayat kunjungan sebelumnya secara ringkas (Tgl Kunjungan \- Vaksin apa saja yang diberikan).