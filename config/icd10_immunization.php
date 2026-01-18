<?php

/**
 * Immunization ICD-10 Codes Configuration
 *
 * Kode ICD-10 khusus untuk imunisasi anak sesuai standar Kemenkes.
 * Data ini akan digunakan untuk searchable select di form imunisasi.
 */
return [
    'Z23' => [
        'code' => 'Z23',
        'name' => 'Need for immunization against single bacterial diseases',
        'description' => 'Kebutuhan imunisasi terhadap penyakit bakteri tunggal (BCG, dll)',
        'keywords' => ['bcg', 'bakteri', 'tuberkulosis', 'tb'],
    ],
    'Z24.0' => [
        'code' => 'Z24.0',
        'name' => 'Need for immunization against poliomyelitis',
        'description' => 'Kebutuhan imunisasi Polio',
        'keywords' => ['polio', 'lumpuh layu', 'ipv', 'opv'],
    ],
    'Z24.6' => [
        'code' => 'Z24.6',
        'name' => 'Need for immunization against viral hepatitis',
        'description' => 'Kebutuhan imunisasi Hepatitis B',
        'keywords' => ['hepatitis', 'hb0', 'hep b', 'hepatitis b'],
    ],
    'Z27.1' => [
        'code' => 'Z27.1',
        'name' => 'Need for immunization against DPT-combined',
        'description' => 'Kebutuhan imunisasi DPT kombinasi (DPT-HB-Hib)',
        'keywords' => ['dpt', 'difteri', 'pertusis', 'tetanus', 'pentavalen', 'hib'],
    ],
    'Z27.4' => [
        'code' => 'Z27.4',
        'name' => 'Need for immunization against measles',
        'description' => 'Kebutuhan imunisasi Campak/MR',
        'keywords' => ['campak', 'measles', 'rubella', 'mr', 'mmr'],
    ],
    'Z00.1' => [
        'code' => 'Z00.1',
        'name' => 'Routine child health examination',
        'description' => 'Pemeriksaan kesehatan rutin anak (Bayi Sehat)',
        'keywords' => ['bayi sehat', 'pemeriksaan rutin', 'wellness', 'tumbuh kembang', 'sehat'],
    ],
];
