# **TASK: CREATE ROBUST EXCEL EXPORT FOR IMMUNIZATION (TEMPLATE-BASED)**

Objective:  
Bangun fitur MonthlyImmunizationExport yang menghasilkan file Excel laporan bulanan imunisasi anak. Fitur ini harus menggunakan pendekatan Template-Based Injection untuk mempertahankan format header kompleks.  
**References:**

1. **Template File:** storage/app/templates/Template Excel Imunisasi.xlsx.  
2. **Reference Logic:** AncRegisterExport.php (Gunakan teknik "Sheet Swapping").

## **1\. TECHNICAL ARCHITECTURE (Reasoning Required)**

The "Sheet Swapping" Algorithm:  
Karena setSpreadsheet tidak reliable, gunakan logika ini di dalam event BeforeExport:

1. **Load Template:** IOFactory::load() file template ke memori.  
2. **Inject Sheet:** $internalSpreadsheet-\>addExternalSheet($templateSheet).  
3. **Clean Up:** $internalSpreadsheet-\>removeSheetByIndex(0).  
4. **Write Data:** Isi data mulai dari baris data (Row 4/5).

## **2\. DATA MAPPING STRATEGY (CRITICAL UPDATE)**

Data Source: ChildVisit Model (Eager load: child, child.patient, immunizationActions).

A. Vertical Iteration (Rows):  
Setiap baris Excel \= Satu Kunjungan Anak (ChildVisit).  
**B. Column Logic (Horizontal Mapping):**

1. **Identitas (Composite Columns):**  
   * **Col A:** No Urut.  
   * **Col B:** Nama Bayi.  
   * **Col C:** NIK Anak.  
   * **Col D:** HP Ibu.  
   * **Col E (Ibu):** Gabungkan "Nama Ibu \\n NIK: \[NIK Ibu\]". (Wrap Text).  
   * **Col F (TTL):** "Tempat, Tgl Lahir".  
2. **Fisik & Gizi:**  
   * **Col G:** Berat Badan.  
   * **Col H:** Panjang Badan.  
   * **Col I:** Lingkar Kepala.  
   * **Col J:** Status Gizi (nutritional\_status).  
3. Vaccine Matrix (EXPLICIT MAPPING STRATEGY):  
   Reasoning: Jangan gunakan if-else manual yang berantakan. Gunakan Associative Array Map untuk mendefinisikan hubungan antara "Nama Vaksin di Database" dengan "Huruf Kolom di Excel".  
   *Ambil daftar vaksin anak pada kunjungan ini:* $takenVaccines \= $visit-\>immunizationActions-\>pluck('vaccine\_type')-\>toArray();  
   *Definisikan Peta Kolom (Sesuaikan koordinat ini dengan Template Excel Asli):*  
   $vaccineMap \= \[  
       'HB 0'        \=\> 'K', // Asumsi Kolom K  
       'BCG'         \=\> 'L',  
       'POLIO 1'     \=\> 'M',  
       'POLIO 2'     \=\> 'N',  
       'POLIO 3'     \=\> 'O',  
       'POLIO 4'     \=\> 'P',  
       'HEXAVALEN 1' \=\> 'Q', // DPT-HB-Hib 1  
       'HEXAVALEN 2' \=\> 'R',  
       'HEXAVALEN 3' \=\> 'S',  
       'IPV 1'       \=\> 'T',  
       'IPV 2'       \=\> 'U',  
       'PCV 1'       \=\> 'V',  
       'PCV 2'       \=\> 'W',  
       'PCV 3'       \=\> 'X',  
       'MR 1'        \=\> 'Y',  
       'MR 2'        \=\> 'Z',  
       'ROTA 1'      \=\> 'AA',  
       'ROTA 2'      \=\> 'AB',  
       'ROTA 3'      \=\> 'AC',  
   \];

   Logic Loop:  
   Iterasi array $vaccineMap. Jika key (Nama Vaksin) ada di dalam $takenVaccines, maka tulis string "v" (atau simbol checklist ✓) pada kolom value-nya.  
4. **Medicine Logic (Parasetamol):**  
   * Cek kolom medicine\_given.  
   * Jika 'Parasetamol Drop' \-\> Tulis medicine\_dosage di Kolom **AD** (misal).  
   * Jika 'Parasetamol Sirup' \-\> Tulis medicine\_dosage di Kolom **AE** (misal).  
   * *Note:* Jangan hanya centang, tapi tulis dosisnya sesuai request "DOSIS BISA DIKETIK AJA YA".

## **3\. IMPLEMENTATION RULES**

1. **Strict Coordinates:** Karena saya tidak melihat file Excel secara visual, Anda (Agent) harus memberikan komentar // TODO: VERIFY COLUMN di sebelah kode mapping kolom vaksin, agar user bisa menggeser huruf kolomnya jika meleset sedikit.  
2. **Checkmark Style:** Gunakan huruf 'v' kecil atau simbol '✓' (UNICHAR) dan set alignment center.  
3. **Safety:** Gunakan strtoupper saat membandingkan nama vaksin untuk menghindari case-sensitivity issues.

## **4\. EXPECTED OUTPUT**

Generate kode app/Exports/MonthlyImmunizationExport.php.  
Kode harus mengandung array $vaccineMap yang jelas di dalam method BeforeExport, sehingga user mudah mengubah pemetaan kolom jika template berubah di masa depan.