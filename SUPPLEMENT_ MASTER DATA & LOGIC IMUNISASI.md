# **SUPPLEMENT: MASTER DATA & LOGIC IMUNISASI**

Context:  
Setelah struktur tabel (Migration) selesai dibuat berdasarkan MODULE\_IMMUNIZATION\_V3.md, kita perlu mengisi data master dan membuat logic backend agar sistem siap pakai.

## **1\. Master Data Seeder (Wajib Ada)**

Agent harus membuat Class Seeder (VaccineSeeder dan Icd10Seeder) untuk mengisi data berikut ke database (bisa dibuat tabel master baru atau hardcode di Enum/Array Service jika tabel master belum ada, tapi disarankan tabel master sederhana atau config array).

**A. Daftar Vaksin Standar (Sesuai Kemenkes):**

* HB0 (Hepatitis B 0\)  
* BCG  
* Polio 1, Polio 2, Polio 3, Polio 4  
* DPT-HB-Hib 1, DPT-HB-Hib 2, DPT-HB-Hib 3  
* IPV (Inactivated Polio Vaccine)  
* MR / Campak

**B. Daftar Kode Diagnosa Imunisasi (ICD-10 dari PDF):**

* Z23 \- Need for immunization against single bacterial diseases  
* Z24.0 \- Need for immunization against poliomyelitis  
* Z24.6 \- Need for immunization against viral hepatitis (Hepatitis)  
* Z27.1 \- Need for immunization against DPT-combined  
* Z27.4 \- Need for immunization against measles (Campak)  
* Z00.1 \- Routine child health examination (Bayi Sehat)

## **2\. Backend Service: RM Generator**

Buat Trait atau Service class GeneratesChildRm.php.

* **Logic:** Generate nomor unik dengan format ANAK-{TAHUN}-{URUTAN}. Contoh: ANAK-2026-0005.  
* Gunakan *Atomic Lock* atau *Transaction* agar tidak ada nomor ganda jika ada 2 bidan input bersamaan.  
* Pasang logic ini di boot() method pada Model Child (event creating).

## **3\. Frontend Helper: Age Calculator**

Di PDF, umur ditampilkan detail: "0 tahun 4 bulan 25 hari".  
Tolong buatkan Helper Function (PHP) di Model Child atau ChildVisit:

* Input: Tanggal Lahir (dob) dan Tanggal Kunjungan (visit\_date).  
* Output: String format "X Tahun Y Bulan Z Hari".  
* Gunakan Carbon::diff().

## **4\. UI Improvement (Select2 / Searchable)**

Untuk input **Diagnosa (ICD-10)** di Form ImmunizationEntry:

* Jangan gunakan Text Input biasa.  
* Gunakan komponen Select yang bisa di-search (karena kode ICD banyak).  
* Jika Bidan ketik "Polio", muncul saran "Z24.0 \- Need for immunization...".