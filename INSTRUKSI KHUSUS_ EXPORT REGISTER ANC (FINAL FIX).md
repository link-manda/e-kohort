# **INSTRUKSI KHUSUS: EXPORT REGISTER ANC (FINAL FIX)**

Context:  
Kita harus meniru format "Register ANC Terintegrasi" yang memiliki Header Bertumpuk (3 Baris Header).  
PENTING: Jangan melewatkan satu kolom pun. Struktur tabel harus persis seperti di bawah ini.  
**Library:** maatwebsite/excel (Gunakan FromView).

## **1\. Struktur Header HTML (Wajib diikuti persis)**

Buat file view resources/views/exports/anc\_register.blade.php.  
Perhatikan penggunaan rowspan dan struktur barisnya agar sesuai dengan Excel Dinas.  
\<table style="border-collapse: collapse; width: 100%;"\>  
    \<thead\>  
        \<\!-- BARIS HEADER 1 \--\>  
        \<tr\>  
            \<th rowspan="3" style="border: 1px solid \#000; vertical-align: middle; text-align: center; font-weight: bold;"\>No\</th\>  
            \<th rowspan="3" style="border: 1px solid \#000; vertical-align: middle; text-align: center; font-weight: bold;"\>Tanggal\<br\>Kunjungan\</th\>  
              
            \<\!-- KOLOM IDENTITAS (BERTUMPUK) \--\>  
            \<th style="border: 1px solid \#000; text-align: center;"\>No RM\</th\>  
            \<th style="border: 1px solid \#000; text-align: center;"\>Nama Ibu / Suami\</th\>  
            \<th style="border: 1px solid \#000; text-align: center;"\>NIK Ibu / Suami\</th\>  
            \<th style="border: 1px solid \#000; text-align: center;"\>Pekerjaan Ibu/Suami\</th\>  
            \<th style="border: 1px solid \#000; text-align: center;"\>Pendidikan Ibu/Suami\</th\>  
            \<th style="border: 1px solid \#000; text-align: center;"\>Umur\</th\>  
            \<th style="border: 1px solid \#000; text-align: center;"\>No HP\</th\>  
            \<th style="border: 1px solid \#000; text-align: center;"\>Alamat Lengkap\</th\>  
              
            \<\!-- KOLOM RIWAYAT \--\>  
            \<th style="border: 1px solid \#000; text-align: center;"\>Gravida\</th\>  
            \<th style="border: 1px solid \#000; text-align: center;"\>HPHT\</th\> \<\!-- Asumsi letak HPHT disini \--\>  
              
            \<\!-- KUNJUNGAN (COLSPAN BESAR) \--\>  
            \<th colspan="8" style="border: 1px solid \#000; text-align: center; font-weight: bold;"\>KUNJUNGAN & HASIL\</th\>  
              
            \<\!-- FISIK \--\>  
            \<th colspan="2" style="border: 1px solid \#000; text-align: center;"\>Berat Badan\</th\>  
            \<th style="border: 1px solid \#000; text-align: center;"\>TB (cm)\</th\>  
            \<th colspan="4" style="border: 1px solid \#000; text-align: center;"\>STATUS GIZI\</th\>  
            \<th style="border: 1px solid \#000; text-align: center;"\>TD (mmHg)\</th\>  
            \<th style="border: 1px solid \#000; text-align: center;"\>MAP Score\</th\> \<\!-- Tambahan Kita \--\>  
            \<th style="border: 1px solid \#000; text-align: center;"\>TFU (cm)\</th\>  
            \<th style="border: 1px solid \#000; text-align: center;"\>DJJ\</th\>  
            \<th style="border: 1px solid \#000; text-align: center;"\>Letak Janin\</th\>  
              
            \<\!-- TINDAKAN \--\>  
            \<th style="border: 1px solid \#000; text-align: center;"\>Imunisasi TT\</th\>  
            \<th style="border: 1px solid \#000; text-align: center;"\>TTD (Jml)\</th\>  
              
            \<\!-- LAB \--\>  
            \<th colspan="6" style="border: 1px solid \#000; text-align: center;"\>LABORATORIUM\</th\>  
            \<th colspan="4" style="border: 1px solid \#000; text-align: center;"\>Status Anemia\</th\>  
            \<th style="border: 1px solid \#000; text-align: center;"\>USG (Ya/Tdk)\</th\>  
              
            \<\!-- ANALISA \--\>  
            \<th style="border: 1px solid \#000; text-align: center;"\>Konseling\</th\>  
            \<th style="border: 1px solid \#000; text-align: center;"\>Resiko\</th\>  
            \<th style="border: 1px solid \#000; text-align: center;"\>Rujukan\</th\>  
            \<th style="border: 1px solid \#000; text-align: center;"\>Diagnosa\</th\>  
            \<th style="border: 1px solid \#000; text-align: center;"\>Tindak Lanjut\</th\>  
            \<th style="border: 1px solid \#000; text-align: center;"\>Nama Nakes\</th\>  
        \</tr\>

        \<\!-- BARIS HEADER 2 \--\>  
        \<tr\>  
            \<\!-- Lanjutan Identitas (Baris 2\) \--\>  
            \<th style="border: 1px solid \#000; text-align: center;"\>No KK\</th\>  
            \<th style="border: 1px solid \#000; background-color: \#f0f0f0;"\>-\</th\> \<\!-- Spacer visual utk Nama \--\>  
            \<th style="border: 1px solid \#000; background-color: \#f0f0f0;"\>-\</th\> \<\!-- Spacer visual utk NIK \--\>  
            \<th style="border: 1px solid \#000; background-color: \#f0f0f0;"\>-\</th\> \<\!-- Spacer visual Pekerjaan \--\>  
            \<th style="border: 1px solid \#000; background-color: \#f0f0f0;"\>-\</th\> \<\!-- Spacer Pendidikan \--\>  
            \<th style="border: 1px solid \#000; text-align: center;"\>TTL\</th\>  
            \<th style="border: 1px solid \#000; background-color: \#f0f0f0;"\>-\</th\>  
            \<th style="border: 1px solid \#000; text-align: center;"\>Domisili\</th\>  
              
            \<\!-- Lanjutan Riwayat \--\>  
            \<th style="border: 1px solid \#000; text-align: center;"\>UK (Mgg)\</th\>  
            \<th style="border: 1px solid \#000; text-align: center;"\>Jarak (Thn)\</th\>  
              
            \<\!-- Detail Kunjungan \--\>  
            \<th style="border: 1px solid \#000; text-align: center;"\>K1\</th\>  
            \<th style="border: 1px solid \#000; text-align: center;"\>K2\</th\>  
            \<th style="border: 1px solid \#000; text-align: center;"\>K3\</th\>  
            \<th style="border: 1px solid \#000; text-align: center;"\>K4\</th\>  
            \<th style="border: 1px solid \#000; text-align: center;"\>K5\</th\>  
            \<th style="border: 1px solid \#000; text-align: center;"\>K6\</th\>  
            \<th style="border: 1px solid \#000; text-align: center;"\>K8\</th\>  
            \<th style="border: 1px solid \#000; text-align: center;"\>ANC 12T\</th\>

            \<\!-- Detail Fisik \--\>  
            \<th style="border: 1px solid \#000; text-align: center;"\>Sblm Hamil\</th\>  
            \<th style="border: 1px solid \#000; text-align: center;"\>Saat Ini\</th\>  
            \<th style="border: 1px solid \#000; background-color: \#f0f0f0;"\>-\</th\>  
              
            \<\!-- Detail Gizi \--\>  
            \<th style="border: 1px solid \#000; text-align: center;"\>IMT\</th\>  
            \<th style="border: 1px solid \#000; text-align: center;"\>LILA\</th\>  
            \<th style="border: 1px solid \#000; text-align: center;"\>KEK\</th\>  
            \<th style="border: 1px solid \#000; text-align: center;"\>Normal\</th\>  
              
            \<\!-- Spacer Vital Signs \--\>  
            \<th style="border: 1px solid \#000; background-color: \#f0f0f0;"\>-\</th\>  
            \<th style="border: 1px solid \#000; background-color: \#f0f0f0;"\>-\</th\>  
            \<th style="border: 1px solid \#000; background-color: \#f0f0f0;"\>-\</th\>  
            \<th style="border: 1px solid \#000; background-color: \#f0f0f0;"\>-\</th\>  
            \<th style="border: 1px solid \#000; background-color: \#f0f0f0;"\>-\</th\>  
              
            \<\!-- Spacer Tindakan \--\>  
            \<th style="border: 1px solid \#000; background-color: \#f0f0f0;"\>-\</th\>  
            \<th style="border: 1px solid \#000; background-color: \#f0f0f0;"\>-\</th\>  
              
            \<\!-- Detail Lab \--\>  
            \<th style="border: 1px solid \#000; text-align: center;"\>HIV\</th\>  
            \<th style="border: 1px solid \#000; text-align: center;"\>Sifilis\</th\>  
            \<th style="border: 1px solid \#000; text-align: center;"\>HBsAg\</th\>  
            \<th style="border: 1px solid \#000; text-align: center;"\>Hb\</th\>  
            \<th style="border: 1px solid \#000; text-align: center;"\>Prot. Urine\</th\>  
            \<th style="border: 1px solid \#000; text-align: center;"\>GolDa (Ibu/Suami)\</th\>  
              
            \<\!-- Detail Anemia \--\>  
            \<th style="border: 1px solid \#000; text-align: center;"\>Tidak\</th\>  
            \<th style="border: 1px solid \#000; text-align: center;"\>Ringan\</th\>  
            \<th style="border: 1px solid \#000; text-align: center;"\>Sedang\</th\>  
            \<th style="border: 1px solid \#000; text-align: center;"\>Berat\</th\>

            \<\!-- Spacer Sisa \--\>  
            \<th colspan="7" style="border: 1px solid \#000; background-color: \#f0f0f0;"\>-\</th\>  
        \</tr\>

        \<\!-- BARIS HEADER 3 \--\>  
        \<tr\>  
            \<th style="border: 1px solid \#000; text-align: center;"\>No BPJS\</th\>  
            \<\!-- Sisa kolom di row 3 biasanya kosong/spacer, kecuali ada sub-header lagi.  
                 Di Excel aslinya, data BPJS ditaruh di baris ke-3 dalam satu cell.  
                 Di tabel HTML, kita biarkan ini sebagai header label untuk kolom tersebut. \--\>  
            \<th colspan="40" style="border: 1px solid \#000; background-color: \#ccc;"\>Area Data (Mapping)\</th\>  
        \</tr\>  
    \</thead\>  
      
    \<\!-- BODY DATA \--\>  
    \<tbody\>  
        @foreach($visits as $i \=\> $visit)  
        \<tr\>  
            \<td style="border: 1px solid \#000;"\>{{ $i \+ 1 }}\</td\>  
            \<td style="border: 1px solid \#000;"\>{{ $visit-\>visit\_date-\>format('d-m-Y') }}\</td\>  
              
            \<\!-- KOLOM BERTUMPUK DI EXCEL (NO RM / KK / BPJS) \--\>  
            \<\!-- Kita render 3 baris data dalam 1 sel menggunakan \<br\> \--\>  
            \<td style="border: 1px solid \#000;"\>  
                {{ $visit-\>patient-\>no\_rm }}\<br\>  
                {{ $visit-\>patient-\>no\_kk }}\<br\>  
                {{ $visit-\>patient-\>no\_bpjs }}  
            \</td\>  
              
            \<td style="border: 1px solid \#000;"\>  
                {{ $visit-\>patient-\>name }}\<br\>  
                {{ $visit-\>patient-\>husband\_name }}  
            \</td\>  
              
            \<td style="border: 1px solid \#000;"\>  
                {{ $visit-\>patient-\>nik }}\<br\>  
                {{ $visit-\>patient-\>husband\_nik }}  
            \</td\>

            \<td style="border: 1px solid \#000;"\>  
                {{ $visit-\>patient-\>job }}\<br\>  
                {{ $visit-\>patient-\>husband\_job }}  
            \</td\>  
              
            \<td style="border: 1px solid \#000;"\>  
                {{ $visit-\>patient-\>education }}\<br\>  
                {{ $visit-\>patient-\>husband\_education }}  
            \</td\>

            \<td style="border: 1px solid \#000;"\>  
                {{ \\Carbon\\Carbon::parse($visit-\>patient-\>dob)-\>age }} Thn\<br\>  
                {{ $visit-\>patient-\>pob }}, {{ $visit-\>patient-\>dob }} \<\!-- TTL \--\>  
            \</td\>  
              
            \<td style="border: 1px solid \#000;"\>{{ $visit-\>patient-\>phone }}\</td\>  
            \<td style="border: 1px solid \#000;"\>{{ $visit-\>patient-\>address }}\</td\>  
              
            \<td style="border: 1px solid \#000;"\>{{ $visit-\>pregnancy-\>gravida }}\</td\>  
            \<td style="border: 1px solid \#000;"\>{{ $visit-\>pregnancy-\>hpht }}\</td\>  
            \<td style="border: 1px solid \#000;"\>{{ $visit-\>gestational\_age }} Mgg\</td\>  
            \<td style="border: 1px solid \#000;"\>{{ $visit-\>pregnancy-\>pregnancy\_gap }} Thn\</td\>  
              
            \<\!-- Checkmark logic for Visit Type \--\>  
            \<td style="border: 1px solid \#000;"\>{{ $visit-\>visit\_code \== 'K1' ? 'V' : '' }}\</td\>  
            \<td style="border: 1px solid \#000;"\>{{ $visit-\>visit\_code \== 'K2' ? 'V' : '' }}\</td\>  
            \<td style="border: 1px solid \#000;"\>{{ $visit-\>visit\_code \== 'K3' ? 'V' : '' }}\</td\>  
            \<td style="border: 1px solid \#000;"\>{{ $visit-\>visit\_code \== 'K4' ? 'V' : '' }}\</td\>  
            \<td style="border: 1px solid \#000;"\>{{ $visit-\>visit\_code \== 'K5' ? 'V' : '' }}\</td\>  
            \<td style="border: 1px solid \#000;"\>{{ $visit-\>visit\_code \== 'K6' ? 'V' : '' }}\</td\>  
            \<td style="border: 1px solid \#000;"\>{{ $visit-\>visit\_code \== 'K8' ? 'V' : '' }}\</td\> \<\!-- Jika ada \--\>  
            \<td style="border: 1px solid \#000;"\>\</td\> \<\!-- ANC 12T logic \--\>

            \<td style="border: 1px solid \#000;"\>{{ $visit-\>pregnancy-\>weight\_before }}\</td\>  
            \<td style="border: 1px solid \#000;"\>{{ $visit-\>weight }}\</td\>  
            \<td style="border: 1px solid \#000;"\>{{ $visit-\>height }}\</td\>  
              
            \<\!-- Gizi \--\>  
            \<td style="border: 1px solid \#000;"\>{{ $visit-\>bmi }}\</td\>  
            \<td style="border: 1px solid \#000;"\>{{ $visit-\>lila }}\</td\>  
            \<td style="border: 1px solid \#000;"\>{{ $visit-\>lila \< 23.5 ? 'V' : '' }}\</td\>  
            \<td style="border: 1px solid \#000;"\>{{ $visit-\>lila \>= 23.5 ? 'V' : '' }}\</td\>

            \<td style="border: 1px solid \#000;"\>{{ $visit-\>systolic }}/{{ $visit-\>diastolic }}\</td\>  
            \<td style="border: 1px solid \#000; font-weight:bold;"\>{{ $visit-\>map\_score }}\</td\>  
            \<td style="border: 1px solid \#000;"\>{{ $visit-\>tfu }}\</td\>  
            \<td style="border: 1px solid \#000;"\>{{ $visit-\>djj }}\</td\>  
            \<td style="border: 1px solid \#000;"\>{{ $visit-\>fetal\_presentation }}\</td\>  
              
            \<td style="border: 1px solid \#000;"\>{{ $visit-\>tt\_imunization }}\</td\>  
            \<td style="border: 1px solid \#000;"\>{{ $visit-\>fe\_tablets }}\</td\>  
              
            \<\!-- Lab \--\>  
            \<td style="border: 1px solid \#000;"\>{{ $visit-\>lab-\>hiv\_status }}\</td\>  
            \<td style="border: 1px solid \#000;"\>{{ $visit-\>lab-\>syphilis\_status }}\</td\>  
            \<td style="border: 1px solid \#000;"\>{{ $visit-\>lab-\>hbsag\_status }}\</td\>  
            \<td style="border: 1px solid \#000;"\>{{ $visit-\>lab-\>hb }}\</td\>  
            \<td style="border: 1px solid \#000;"\>{{ $visit-\>lab-\>protein\_urine }}\</td\>  
            \<td style="border: 1px solid \#000;"\>{{ $visit-\>patient-\>blood\_type }} / {{ $visit-\>patient-\>husband\_blood\_type }}\</td\>  
              
            \<\!-- Anemia Check \--\>  
            \<td style="border: 1px solid \#000;"\>{{ $visit-\>lab-\>hb \>= 11 ? 'V' : '' }}\</td\>  
            \<td style="border: 1px solid \#000;"\>{{ ($visit-\>lab-\>hb \>= 9 && $visit-\>lab-\>hb \< 11\) ? 'V' : '' }}\</td\>  
            \<td style="border: 1px solid \#000;"\>{{ ($visit-\>lab-\>hb \>= 7 && $visit-\>lab-\>hb \< 9\) ? 'V' : '' }}\</td\>  
            \<td style="border: 1px solid \#000;"\>{{ ($visit-\>lab-\>hb \< 7 && $visit-\>lab-\>hb \> 0\) ? 'V' : '' }}\</td\>

            \<td style="border: 1px solid \#000;"\>{{ $visit-\>usg\_check \== 1 ? 'Ya' : 'Tidak' }}\</td\>  
            \<td style="border: 1px solid \#000;"\>{{ $visit-\>counseling\_check \== 1 ? 'V' : '' }}\</td\>  
            \<td style="border: 1px solid \#000;"\>{{ $visit-\>risk\_level }}\</td\>  
            \<td style="border: 1px solid \#000;"\>{{ $visit-\>referral\_target ? 'Ya' : 'Tidak' }}\</td\>  
            \<td style="border: 1px solid \#000;"\>{{ $visit-\>diagnosis }}\</td\>  
            \<td style="border: 1px solid \#000;"\>{{ $visit-\>follow\_up }}\</td\>  
            \<td style="border: 1px solid \#000;"\>{{ $visit-\>midwife\_name }}\</td\>  
        \</tr\>  
        @endforeach  
    \</tbody\>  
\</table\>  
