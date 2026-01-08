# **PROJECT CONTEXT: E-Kohort Bidan (Laravel \- Final Schema V2)**

## **1\. Instruksi Database Migration (Wajib Sama Persis Template Dinas)**

Agent harus membuat/memperbaiki file migration agar kolom-kolom berikut tersedia. Jangan ada yang terlewat.

### **A. Tabel patients (Identitas Ibu & Suami)**

* id (BigInt AI)  
* no\_rm (String \- Nomor Rekam Medis) \-\> **PENTING**  
* no\_kk (String \- Nomor Kartu Keluarga) \-\> **PENTING**  
* no\_bpjs (String \- Nomor JKN/KIS) \-\> **PENTING**  
* nik (String 16 digit), name (Nama Ibu)  
* dob (Date \- Tgl Lahir), pob (Tempat Lahir)  
* address (Text \- Alamat Domisili), phone (String \- No HP/WA)  
* job (String \- Pekerjaan Ibu)  
* education (String \- Pendidikan Terakhir Ibu)  
* blood\_type (String \- Golongan Darah Ibu)  
* **Data Suami (Wajib Ada):**  
  * husband\_name (String)  
  * husband\_nik (String)  
  * husband\_job (String)  
  * husband\_education (String)  
  * husband\_blood\_type (String) \-\> **PENTING**

### **B. Tabel pregnancies (Riwayat Kehamilan)**

* id, patient\_id  
* gravida (String \- G\_P\_A\_)  
* hpht (Date), hpl (Date)  
* pregnancy\_gap (Integer \- Jarak Kehamilan dalam Tahun) \-\> **PENTING (Kolom "JARAK")**  
* weight\_before (Float \- BB Sebelum Hamil)  
* height (Float \- TB Ibu)

### **C. Tabel anc\_visits (Data Kunjungan Rutin)**

* id, pregnancy\_id, visit\_date  
* gestational\_age (Integer \- UK Minggu)  
* visit\_code (Enum/String: K1, K2, K3, K4, K5, K6)  
* anc\_12t (Boolean \- Standar 12T) \-\> **PENTING**  
* **Fisik:**  
  * weight (BB Sekarang), lila (Lingkar Lengan)  
  * bmi (Float \- IMT)  
  * systolic, diastolic (Integer)  
  * map\_score (Float \- Wajib hitung MAP \= Diastol \+ 1/3(Sistol-Diastol))  
  * tfu (Integer \- cm), djj (Integer \- bpm)  
  * fetal\_presentation (String \- Letak Janin: Kepala/Sungsang/Lintang)  
* **Tindakan:**  
  * tt\_imunization (String \- Status TT)  
  * fe\_tablets (Integer \- Jumlah Tablet Tambah Darah)  
  * usg\_check (Boolean \- USG Ya/Tidak) \-\> **PENTING**  
  * counseling\_check (Boolean \- Konseling/KIE)  
* **Analisa:**  
  * risk\_level (String \- Deteksi Resiko)  
  * diagnosis (Text)  
  * referral\_target (String \- Rujukan Ya/Tidak/Tujuan)  
  * follow\_up (Text \- Tindak Lanjut)  
  * midwife\_name (String \- Nama Nakes)

### **D. Tabel lab\_results**

* anc\_visit\_id  
* hb (Float \- Hemoglobin)  
* protein\_urine (String)  
* hiv\_status (String: NR/R), syphilis\_status (String: NR/R), hbsag\_status (String: NR/R)  
* anemia\_status (String \- Calculated field: Normal/Ringan/Sedang/Berat)

## **2\. Instruksi Form Input (Livewire)**

Saat membuat Form Wizard, pastikan field-field di atas (terutama Data Suami, No RM, dan USG) memiliki input field-nya masing-masing.