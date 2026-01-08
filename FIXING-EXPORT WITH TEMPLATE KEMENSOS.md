KONDISI SAAT INI:
Kode export Excel yang ada saat ini SALAH karena menggunakan:

-   FromCollection dan/atau FromView dan/atau WithHeadings

TUGAS ANDA:
LAKUKAN AUTO-REFACTOR TOTAL terhadap kode tersebut.

ATURAN WAJIB (JANGAN DILANGGAR):

-   HAPUS seluruh implementasi FromCollection
-   HAPUS FromView, WithHeadings, FromArray jika ada
-   JANGAN membuat spreadsheet baru dari nol
-   JANGAN membuat atau mengubah header, style, atau merge cell lewat kode
-   JANGAN mengubah struktur Excel

PENDEKATAN BARU YANG WAJIB:

-   Gunakan Laravel Excel (Maatwebsite) + PhpSpreadsheet
-   Load file template Excel (.xlsx) yang sudah ada menggunakan IOFactory::load()
-   Lokasi template: storage/app/templates
-   Gunakan WithEvents dan event BeforeExport
-   Isi data ke cell yang sudah ada mulai dari baris data yang ditentukan
-   Pertahankan SEMUA merged cell, tier header, style, dan formula dari template
-   Gunakan $event->writer->setSpreadsheet($spreadsheet)

OUTPUT YANG DIHARAPKAN:

-   Versi KODE BARU hasil refactor
-   Class Export lengkap yang BENAR
-   Jika perlu, sesuaikan constructor untuk menerima data
-   JANGAN sertakan solusi alternatif
-   JANGAN mempertahankan kode lama yang salah

VALIDASI WAJIB:

-   Tidak ada lagi FromCollection / FromView / WithHeadings
-   Ada IOFactory::load()
-   Ada WithEvents + BeforeExport
-   Template Excel tetap menjadi sumber layout utama
