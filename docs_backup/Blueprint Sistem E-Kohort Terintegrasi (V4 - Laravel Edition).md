# **Blueprint Sistem E-Kohort Terintegrasi (V4 \- Laravel Edition)**

Tech Stack: Laravel 12, MySQL, Livewire, Tailwind CSS.  
Fokus: Pencatatan Ibu Hamil (ANC), Nifas, dan KB dengan standar medis terbaru (MAP & Triple Eliminasi).

## **1\. Arsitektur Sistem (Monolith Modular)**

Kita menggunakan pendekatan **Monolith** yang modern. Semua logika ada dalam satu project Laravel, namun dipisah berdasarkan modul untuk kerapian.

* **Frontend Layer:** Blade Templates \+ **Livewire**.  
  * *Kenapa Livewire?* Kita bisa membuat fitur interaktif (seperti kalkulator MAP real-time) tanpa menulis JavaScript yang rumit. Cukup gunakan PHP.  
* **Business Logic Layer:** Service Classes / Traits.  
  * Logic perhitungan medis (MAP, HPL, Status Gizi) dipisah dari Controller agar bisa dipakai ulang.  
* **Database Layer:** MySQL dengan Eloquent ORM.

## **2\. Desain Database (Schema & Relasi)**

Struktur tabel dirancang untuk mendukung *Continuity of Care* (Data berlanjut dari Hamil \-\> Nifas \-\> KB).

### **A. Tabel Master (Data Induk)**

#### **1\. users (Tabel Bawaan Laravel)**

* Menyimpan data Bidan/Petugas.  
* **Role:** Admin (Dinas), Bidan Koordinator, Bidan Desa.

#### **2\. patients (Data Ibu)**

* Menyimpan data demografis yang statis (jarang berubah).  
* **Columns:**  
  * id (Primary Key)  
  * nik (Unique, Index \- 16 chars)  
  * no\_kk  
  * no\_bpjs  
  * name  
  * dob (Tanggal Lahir)  
  * address (Alamat Domisili)  
  * phone (WhatsApp)  
  * blood\_type (Gol Darah)  
  * husband\_name  
  * husband\_nik  
  * husband\_job

### **B. Tabel Transaksional (Siklus Reproduksi)**

#### **3\. pregnancies (Header Kehamilan)**

* Satu Ibu (patient\_id) bisa punya banyak Kehamilan. Tabel ini membedakan Anak ke-1, ke-2, dst.  
* **Columns:**  
  * id (PK)  
  * patient\_id (Foreign Key \-\> patients.id)  
  * gravida (String, cth: "G1P0A0")  
  * hpht (Date \- Hari Pertama Haid Terakhir)  
  * hpl (Date \- Hari Perkiraan Lahir)  
  * pregnancy\_gap (Int \- Jarak Kehamilan dalam tahun)  
  * status (Enum: 'Aktif', 'Lahir', 'Abortus')  
  * risk\_score\_initial (Skor Poedji Rochjati \- opsional)

#### **4\. anc\_visits (Detail Kunjungan \- Sesuai Kolom Excel)**

* Satu Kehamilan (pregnancy\_id) punya banyak Kunjungan (K1, K2... K6).  
* **Columns:**  
  * id (PK)  
  * pregnancy\_id (FK \-\> pregnancies.id)  
  * visit\_date (Date)  
  * trimester (Int: 1, 2, 3\)  
  * visit\_code (Enum: 'K1', 'K2', 'K3', 'K4', 'K5', 'K6')  
  * gestational\_age (Int \- Minggu)  
  * **Fisik & MAP:**  
    * weight (Desimal \- kg)  
    * height (Desimal \- cm)  
    * lila (Desimal \- cm) \-\> Logic: Jika \< 23.5 flag KEK.  
    * systolic (Int)  
    * diastolic (Int)  
    * map\_score (Desimal \- Hasil Kalkulasi MAP) \-\> Logic: \>100 Alert.  
    * tfu (Int \- cm)  
    * djj (Int \- Detak Jantung Janin)  
  * **Lab & Integrasi (Triple Eliminasi):**  
    * hb (Desimal \- Hemoglobin)  
    * protein\_urine (String: Negatif, \+1, \+2, \+3)  
    * hiv\_status (Enum: 'NR', 'R', 'Unchecked')  
    * syphilis\_status (Enum: 'NR', 'R', 'Unchecked')  
    * hbsag\_status (Enum: 'NR', 'R', 'Unchecked')  
  * **Tindakan & Analisa:**  
    * tt\_imunization (Enum: T1-T5)  
    * fe\_tablets (Int \- Jumlah TTD)  
    * risk\_category (Enum: 'Rendah', 'Tinggi', 'Ekstrem')  
    * diagnosis (Text)  
    * referral\_target (String \- Tujuan Rujukan jika ada)

### **C. Tabel Fase Lanjutan (Roadmap)**

#### **5\. postnatal\_care (Nifas/KF)**

* Terhubung ke pregnancies (karena nifas bagian dari akhir kehamilan).  
* Columns: kf\_code (KF1-KF4), vitamin\_a (Boolean), complications.

#### **6\. family\_planning (KB)**

* Terhubung ke patients (karena KB bersifat jangka panjang).  
* Columns: method (Suntik, Pil, IUD), start\_date, next\_visit.

## **3\. Komponen Logika Medis (Laravel Traits)**

Di Laravel, kita akan membuat Trait (Helper Code) agar logika medis tidak campur aduk di Controller.

### **A. CalculatesMapScore.php**

trait CalculatesMapScore {  
    public function calculateMAP($systol, $diastol) {  
        // MAP \= Diastol \+ 1/3(Sistol \- Diastol)  
        return round($diastol \+ (($systol \- $diastol) / 3), 2);  
    }  
      
    public function getMapRiskLevel($mapScore) {  
        if ($mapScore \> 100\) return 'BAHAYA'; // Red  
        if ($mapScore \> 90\) return 'WASPADA'; // Yellow  
        return 'NORMAL'; // Green  
    }  
}

### **B. DetectsRisk.php**

Logic otomatis untuk menentukan bendera merah:

1. Jika map\_score \> 100 OR systolic \>= 140\.  
2. Jika lila \< 23.5 (KEK).  
3. Jika hb \< 11 (Anemia).  
4. Jika salah satu Triple Eliminasi \= 'R' (Reaktif).

## **4\. Alur Penggunaan (User Flow)**

1. **Dashboard:** Bidan melihat statistik hari ini & daftar pasien "Warning" (MAP Tinggi).  
2. **Pendaftaran:** \* Cari NIK Pasien.  
   * Jika ada \-\> Langsung tambah kunjungan.  
   * Jika tidak \-\> Input Identitas Baru \-\> Input Kehamilan \-\> Input Kunjungan.  
3. **Input Kunjungan (The Wizard):**  
   * **Step 1:** Update keluhan & UK (Usia Kehamilan).  
   * **Step 2:** Input Fisik (Tensi diinput \-\> MAP otomatis muncul di layar via Livewire).  
   * **Step 3:** Input Lab (Ceklis Triple Eliminasi).  
   * **Step 4:** Sistem memberi saran diagnosa (Misal: "Saran: Rujuk karena MAP Tinggi"). Bidan simpan.  
4. **Export:** Bidan klik "Download Excel" \-\> Laravel meng-generate file .xlsx sesuai format Dinas.

## **5\. Security & Deployment**

* **Role & Permission:** Menggunakan Laravel Gates/Policies. Bidan Desa hanya bisa edit data desanya sendiri.  
* **Data Protection:** Password di-hash (Bcrypt). NIK di-index untuk pencarian cepat.  
* **Deployment:** Bisa dihosting di VPS murah atau Shared Hosting (karena PHP/MySQL support-nya sangat umum).