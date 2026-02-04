<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class Icd10Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Lokasi file CSV
        // Pastikan Anda meletakkan file CSV di folder: database/seeders/data/
        // dan ubah nama filenya menjadi ICD10.csv agar lebih mudah
        $csvFile = database_path('seeders/data/ICD10.csv');

        // Cek apakah file ada
        if (!File::exists($csvFile)) {
            $this->command->error("File CSV tidak ditemukan di: $csvFile");
            $this->command->info("Silakan letakkan file CSV Anda di lokasi tersebut.");
            return;
        }

        // 2. Kosongkan tabel sebelum import untuk menghindari duplikat
        DB::table('icd10_codes')->truncate();

        // Matikan query log untuk menghemat memori saat proses data besar
        DB::disableQueryLog();

        $this->command->info('Mulai mengimport data ICD-10...');

        // 3. Baca File CSV
        $fileStream = fopen($csvFile, 'r');

        // Skip baris pertama (Header: CODE, DISPLAY, VERSION)
        fgetcsv($fileStream);

        $chunkSize = 1000; // Masukkan per 1000 baris agar cepat
        $dataBuffer = [];
        $count = 0;
        $timestamp = now();

        while (($row = fgetcsv($fileStream)) !== false) {
            // Mapping kolom CSV ke Database
            // Index 0 = CODE (Contoh: A00)
            // Index 1 = DISPLAY (Contoh: Cholera)
            // Index 2 = VERSION (Contoh: ICD10_2010) - Tidak dipakai di schema tabel Anda

            $code = $row[0] ?? null;
            $name = $row[1] ?? null;

            if ($code && $name) {
                $dataBuffer[] = [
                    'code'        => $code,
                    'name'        => $name,
                    // Karena di CSV hanya ada kolom DISPLAY (Bahasa Inggris),
                    // kita isi description dengan nilai yang sama.
                    // Jika Anda punya file terpisah untuk Bahasa Indonesia, logikanya perlu disesuaikan.
                    'description' => $name,
                    'category'    => 'general', // Default category
                    'keywords'    => json_encode([]), // Default empty json
                    'is_active'   => true,
                    'created_at'  => $timestamp,
                    'updated_at'  => $timestamp,
                ];
            }

            // Jika buffer sudah mencapai chunkSize, insert ke database
            if (count($dataBuffer) >= $chunkSize) {
                DB::table('icd10_codes')->insert($dataBuffer);
                $count += count($dataBuffer);
                $this->command->info("Berhasil mengimport $count baris...");
                $dataBuffer = []; // Reset buffer
            }
        }

        // Insert sisa data yang ada di buffer (kurang dari 1000)
        if (!empty($dataBuffer)) {
            DB::table('icd10_codes')->insert($dataBuffer);
            $count += count($dataBuffer);
        }

        fclose($fileStream);

        $this->command->info("Selesai! Total $count kode ICD-10 berhasil diimport.");
    }
}