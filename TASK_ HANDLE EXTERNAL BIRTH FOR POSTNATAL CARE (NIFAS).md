# **TASK: HANDLE EXTERNAL BIRTH FOR POSTNATAL CARE (NIFAS)**

**Context:**  
Sistem saat ini mengharuskan pasien memiliki data Pregnancy dan DeliveryRecord lokal untuk bisa input Nifas (PostnatalVisit).  
Masalah: Banyak pasien melahirkan di RS lain (External) tapi kontrol Nifas di klinik kita. Kita butuh fitur untuk mencatat "Riwayat Persalinan Luar" secara cepat sebagai syarat masuk modul Nifas.  
**Goal:** Memodifikasi PostnatalEntry agar bisa menangani pasien yang belum terdata persalinannya.

## **1\. BACKEND LOGIC (Service)**

Buat method baru di PostnatalService atau DeliveryService: createExternalDeliveryHistory().  
**Input:**

* patient\_id  
* delivery\_date (Wajib)  
* delivery\_place (Opsional)  
* baby\_gender (Opsional)

**Logic (Transactions):**

1. **Create Pregnancy:** Buat data baru di tabel pregnancies.  
   * patient\_id: Sesuai input.  
   * hpht: NULL (Karena pasien luar mungkin lupa, atau hitung mundur kasar dari tgl lahir).  
   * status: 'Lahir'.  
   * notes: 'Persalinan Luar / Rujukan Balik'.  
2. **Create DeliveryRecord:** Buat data di tabel delivery\_records.  
   * pregnancy\_id: ID dari step 1\.  
   * delivery\_date\_time: Sesuai input.  
   * is\_external: True (Tambahkan kolom boolean ini di migrasi jika perlu, atau gunakan flag di notes).  
3. **Return:** Object Pregnancy yang baru dibuat.

## **2\. FRONTEND UI (Livewire: PostnatalEntry)**

Refactor komponen mount() dan view-nya.  
**A. Logic mount():**

* Cari Pregnancy terakhir pasien yang statusnya 'Lahir'.  
* Jika **DITEMUKAN**: Load form Nifas seperti biasa.  
* Jika **TIDAK DITEMUKAN**:  
  * Set variabel $showExternalHistoryForm \= true.  
  * Jangan redirect atau error.

**B. Tampilan (View Blade):**  
Tambahkan **Modal** atau **Card Peringatan** yang muncul jika $showExternalHistoryForm aktif.

* **Header:** "Data Persalinan Belum Ditemukan"  
* **Message:** "Pasien ini belum tercatat melahirkan di sistem ini. Jika pasien melahirkan di tempat lain, silakan isi data singkat berikut untuk membuka layanan Nifas."  
* **Form Input Singkat:**  
  * Tanggal & Jam Lahir (Datetime Picker).  
  * Tempat Bersalin (Text).  
  * Jenis Kelamin Bayi (L/P).  
* **Button:** "Simpan Riwayat & Lanjut Nifas".

**C. Action Simpan:**

* Panggil service createExternalDeliveryHistory.  
* Setelah sukses, set $showExternalHistoryForm \= false.  
* Refresh komponen, otomatis load form Nifas (KF1/KF2/dst).

## **3\. DATABASE MIGRATION (Optional)**

Jika diperlukan, tambahkan kolom is\_external (boolean, default false) pada tabel pregnancies atau delivery\_records untuk membedakan mana pasien yang lahiran di klinik kita vs lahiran di luar.