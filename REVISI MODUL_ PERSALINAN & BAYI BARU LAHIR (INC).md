# **REVISI MODUL: PERSALINAN & BAYI BARU LAHIR (INC)**

**Context:**  
Modul Persalinan saat ini terlalu sederhana (hanya tersimpan di tabel pregnancies).  
Berdasarkan standar "Register Persalinan" Bidan, kita membutuhkan pencatatan detail mengenai proses persalinan (Kala I \- IV) dan kondisi bayi saat lahir (untuk bahan pembuatan Surat Keterangan Lahir).  
**Goal:** Membuat tabel khusus persalinan yang detail dan form input yang sesuai alur medis ("Partograf" ringkas).

## **1\. DATABASE SCHEMA (New Table)**

Agent harus membuat migration untuk tabel delivery\_records.  
*Relasi: One-to-One dengan pregnancies.*

### **A. Tabel delivery\_records**

* id (BigInt)  
* pregnancy\_id (FK \-\> pregnancies).  
* delivery\_date\_time (DateTime) \-\> *Tanggal & Jam Lahir (PENTING)*.  
* gestational\_age (Integer) \-\> *Umur Kehamilan (Minggu) saat lahir*.  
* birth\_attendant (String) \-\> *Penolong: Bidan/Dokter*.  
* place\_of\_birth (String).  
* **Ibu (Kala I \- IV):**  
  * duration\_first\_stage (String/Int) \-\> *Lama Kala I (Jam)*.  
  * duration\_second\_stage (String/Int) \-\> *Lama Kala II (Menit)*.  
  * delivery\_method (Enum: 'Spontan Belakang Kepala', 'Sungsang', 'Vakum', 'Sectio Caesarea').  
  * placenta\_delivery (Enum: 'Spontan', 'Manual', 'Sisa').  
  * perineum\_rupture (Enum: 'Utuh', 'Derajat 1', 'Derajat 2', 'Derajat 3', 'Derajat 4', 'Episiotomi').  
  * bleeding\_amount (Integer) \-\> *Estimasi Perdarahan (ml)*.  
  * blood\_pressure (String) \-\> *Tensi Pasca Salin (Kala IV)*.  
* **Bayi (Kondisi Lahir):**  
  * baby\_name (String, nullable) \-\> *Seringkali "By. Ny. X"*.  
  * gender (Enum: 'L', 'P').  
  * birth\_weight (Float) \-\> *BB Lahir (Gram)*.  
  * birth\_length (Float) \-\> *PB Lahir (cm)*.  
  * head\_circumference (Float) \-\> *Lingkar Kepala (cm)*.  
  * apgar\_score\_1 (Integer) \-\> *Menit ke-1*.  
  * apgar\_score\_5 (Integer) \-\> *Menit ke-5*.  
  * condition (Enum: 'Hidup', 'Meninggal', 'Asfiksia').  
  * congenital\_defect (Text, nullable) \-\> *Kelainan bawaan*.  
* **Manajemen Bayi Baru Lahir (Checklist):**  
  * imd\_initiated (Boolean) \-\> *Inisiasi Menyusu Dini \< 1 Jam*.  
  * vit\_k\_given (Boolean) \-\> *Injeksi Vitamin K1*.  
  * eye\_ointment\_given (Boolean) \-\> *Salep Mata*.  
  * hb0\_given (Boolean) \-\> *Imunisasi Hepatitis B0*.

## **2\. DATA CONSISTENCY LOGIC (Observer/Service)**

Saat data delivery\_records disimpan:

1. **Update Pregnancy:** Set pregnancies.status \= 'Lahir'.  
2. **Auto-Create Child:** Sistem harus otomatis membuat data di tabel children (Modul Imunisasi) berdasarkan data bayi ini (Nama, Tgl Lahir, BB, PB, JK).  
   * *Ini fitur "Magic" agar Bidan tidak perlu input ulang data bayi di menu Imunisasi.*  
3. **Auto-Create Immunization:** Jika hb0\_given \= true, otomatis catat riwayat HB0 di tabel immunization\_actions.

## **3\. FRONTEND UI (Livewire: DeliveryEntry)**

Refactor form Persalinan menjadi 3 Section Vertikal:

### **Section 1: Data Persalinan (Ibu)**

* Input Tanggal & Jam (Datetime Picker).  
* Input Cara Lahir, Penolong.  
* Input Keadaan Jalan Lahir (Robekan Perineum & Jahitan).  
* Input Perdarahan (Alert Merah jika \> 500ml).

### **Section 2: Data Bayi (Outcome)**

* Input JK, BB, PB.  
* Input Kondisi (Langsung Menangis/Tidak).  
* **Toggle Switch:**  
  * \[x\] IMD Dilakukan?  
  * \[x\] Vit K Diberikan?  
  * \[x\] HB0 Diberikan?

### **Section 3: Kesimpulan**

* Status Ibu: Sehat / Rujuk.  
* Status Bayi: Sehat / Rujuk / Meninggal.