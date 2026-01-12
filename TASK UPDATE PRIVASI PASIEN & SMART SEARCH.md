TASK: UPDATE PRIVASI PASIEN & SMART SEARCH

Context Bisnis (PENTING):
Klien (Bidan) melaporkan bahwa di lapangan, banyak pasien sensitif (seperti PSK atau korban kekerasan) yang enggan memberikan KTP/NIK. Demi memastikan mereka tetap terlayani (Coverage > Administrasi), kita mengubah aturan main identifikasi pasien.

Tujuan Teknis:

Database: Kolom nik tidak lagi wajib (Nullable), tapi phone (WhatsApp) menjadi WAJIB (Required) sebagai pengganti identitas unik.

Validasi: Update rules di Form Input.

Smart Search: Memperbaiki fitur pencarian agar bisa membedakan nama pasaran (misal: "Komang") dengan memprioritaskan pencarian via No HP atau No RM.

INSTRUKSI 1: DATABASE REFACTORING

Tolong buatkan file Migration baru dengan nama make_nik_nullable_and_phone_required_in_patients_table.

Ubah kolom nik menjadi nullable().

Ubah kolom phone menjadi nullable(false) (Wajib isi).

Pastikan menggunakan Schema::table dan method change().

INSTRUKSI 2: UPDATE VALIDASI FORM (Livewire)

Cari file Component Form Pasien (misal PatientForm.php atau AncWizard.php).
Update fungsi rules():

nik: Hapus required, ganti jadi nullable. Tetap pertahankan validasi numeric dan digits:16 JIKA user mengisinya.

phone: Tambahkan required. Pastikan validasi numeric.

INSTRUKSI 3: IMPLEMENTASI SMART SEARCH

Refactor method render() pada App\Livewire\PatientSearch.php.
Gunakan logika prioritas berikut agar pencarian akurat:

Logic Query:
Jika user mengetik Keyword $k:

Cek Pola RM: Jika $k mengandung "RM-" (case insensitive), cari hanya di kolom no_rm.

Cek Numerik: Jika $k adalah angka (misal "0812..."), cari prioritas utama di kolom phone. Cari juga di nik sebagai opsi kedua (OR WHERE).

Default String: Jika $k adalah huruf, cari di kolom name menggunakan LIKE %...%.

Tampilan (View):
Di file blade pencarian, tampilkan badge/label kecil di samping nama:

Jika NIK kosong, tampilkan Badge Kuning: "Tanpa NIK".

Tampilkan No HP di bawah nama agar Bidan bisa konfirmasi: "Ibu Komang (0812-xxxx-xxxx)".
