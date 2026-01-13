# **MODUL BARU: PERSALINAN & NIFAS (Postnatal Care)**

Context:  
Saat ini sistem baru memiliki data ANC (Ibu Hamil). Kita perlu modul untuk mencatat Persalinan (agar status kehamilan selesai) dan pemantauan Nifas (42 hari setelah lahir).

## **1\. Database Schema Refactor & New Tables**

Agent harus melakukan update tabel pregnancies dan membuat tabel baru postnatal\_visits.

### **A. Update Tabel pregnancies (Mencatat Kelahiran)**

Tambahkan kolom berikut (nullable) untuk mencatat hasil akhir kehamilan:

* delivery\_date (DateTime \- Waktu Lahir).  
* delivery\_method (Enum: Normal, Caesar/Sectio, Vakum).  
* birth\_attendant (String \- Penolong Persalinan: Bidan/Dokter).  
* place\_of\_birth (String \- Tempat Lahir).  
* outcome (Enum: Hidup, Meninggal, Abortus).  
* baby\_gender (Enum: L, P) \-\> *Trigger untuk create data anak nanti*.  
* complications (Text).

### **B. Tabel postnatal\_visits (Kunjungan Nifas / KF)**

Relasi: belongsTo Pregnancy. (Karena Nifas terikat pada kehamilan yang baru selesai).

* id (BigInt)  
* pregnancy\_id (FK).  
* visit\_date (Date).  
* visit\_code (Enum: KF1, KF2, KF3, KF4).  
  * *Logic KF:* KF1 (6 jam-2 hari), KF2 (3-7 hari), KF3 (8-28 hari), KF4 (29-42 hari).  
* **Pemeriksaan Fisik Ibu:**  
  * td\_systolic, td\_diastolic (Cek Tensi \- Waspada Preeklamsia Postpartum).  
  * temperature (Suhu \- Waspada Infeksi Nifas).  
  * lochea (Enum: Rubra, Sanguinolenta, Serosa, Alba) \-\> *Cairan nifas*.  
  * uterine\_involution (String \- Tinggi Fundus mengecil).  
* **Tindakan:**  
  * vitamin\_a (Boolean \- Ibu Nifas wajib dapat Vit A).  
  * fe\_tablets (Integer).  
  * complication\_check (Boolean \- Pendarahan, dll).  
* conclusion (Text \- Sehat/Rujuk).

## **2\. Business Logic (Livewire Service)**

### **A. Fitur "Tutup Kehamilan" (Close Pregnancy)**

Buat Component Livewire DeliveryEntry.

* Form ini muncul jika status pasien masih "Hamil".  
* Input: Tanggal Lahir, Cara Lahir, Kondisi Bayi.  
* **Logic Penting:** Saat disimpan, update status di tabel pregnancies menjadi 'Lahir'.  
* **Auto-Create Child:** (Opsional/Next Step) Idealnya saat simpan persalinan, sistem otomatis membuat row kosong di tabel children untuk persiapan imunisasi.

### **B. Fitur "Input Kunjungan Nifas"**

Buat Component Livewire PostnatalEntry.

* Hanya bisa diisi jika status pregnancy \= 'Lahir'.  
* Validasi Tanggal: Peringatan jika tanggal kunjungan tidak sesuai jadwal KF1-KF4.

## **3\. UI Guidelines**

* Gunakan layout Wizard atau Tabs seperti ANC.  
* Tab 1: Data Persalinan (Hanya diisi sekali).  
* Tab 2: Riwayat Kunjungan Nifas (List KF1-KF4).