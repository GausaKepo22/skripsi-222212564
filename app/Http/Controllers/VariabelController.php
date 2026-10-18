<?php

namespace App\Http\Controllers;

class VariabelController extends Controller
{
    public function index()
    {
        // Mengarahkan langsung ke folder public/data/variabel.csv
        $filePath = public_path('data/variabel.csv');

        if (!file_exists($filePath)) {
            abort(404, 'File public/data/variabel.csv tidak ditemukan');
        }

        $handle = fopen($filePath, 'r');

        // Read header with delimiter ';'
        $rawHeader = fgetcsv($handle, 0, ';');

        if (!$rawHeader) {
            fclose($handle);
            abort(500, 'File CSV kosong atau format delimiter salah.');
        }

        // Clean UTF-8 BOM dari kolom pertama
        $rawHeader[0] = preg_replace('/^\xEF\xBB\xBF/', '', $rawHeader[0]);

        $headers = array_map(function ($h) {
            return strtoupper(trim($h));
        }, $rawHeader);

        $rows = [];
        while (($row = fgetcsv($handle, 0, ';')) !== false) {
            if (count($headers) === count($row)) {
                $rows[] = array_combine($headers, $row);
            }
        }
        fclose($handle);

        // Allowed columns
        $allowed = [
            'RESTORAN', 'BANK', 'MENARA TELEPON', 'OPERATOR SELULER', 'ATM',
            'DTW_STASIUN', 'MEAN_WR', 'PERTOKOAN', 'MINIMARKET',
            'KEDAI_MAKAN_MINUM', 'HOTEL', 'PENGINAPAN', 'TOKO/WARUNG',
            'ALAM', 'BUATAN', 'BUDAYA', 'REKREASI', 'TIRTA'
        ];

        $allowed = array_map(fn($a) => strtoupper(trim($a)), $allowed);

        $labels = [
            'RESTORAN' => 'JUMLAH RESTORAN',
            'BANK' => 'JUMLAH BANK',
            'MENARA TELEPON' => 'JUMLAH MENARA TELEPON',
            'OPERATOR SELULER' => 'JUMLAH OPERATOR SELULER',
            'ATM' => 'JUMLAH ATM/BRILINK',
            'DTW_STASIUN' => 'RATA-RATA JARAK DTW KE STASIUN TERDEKAT (KM)',
            'MEAN_WR' => 'RATA-RATA WEIGHTING RATE (WR)',
            'PERTOKOAN' => 'JUMLAH PERTOKOAN',
            'MINIMARKET' => 'JUMLAH MINIMARKET',
            'KEDAI_MAKAN_MINUM' => 'JUMLAH KEDAI MAKAN & MINUM',
            'HOTEL' => 'JUMLAH HOTEL',
            'PENGINAPAN' => 'JUMLAH PENGINAPAN',
            'TOKO/WARUNG' => 'JUMLAH TOKO / WARUNG',
            'ALAM' => 'JUMLAH WISATA ALAM',
            'BUATAN' => 'JUMLAH WISATA BUATAN',
            'BUDAYA' => 'JUMLAH WISATA BUDAYA',
            'REKREASI' => 'JUMLAH WISATA REKREASI',
            'TIRTA' => 'JUMLAH WISATA TIRTA'
        ];

        $dataset = [];

        foreach ($headers as $colName) {
            $colName = strtoupper(trim($colName));
            if (in_array($colName, $allowed)) {
                $dataset[$colName] = [
                    'label' => $labels[$colName] ?? $colName,
                    'data' => []
                ];
            }
        }

        foreach ($rows as $row) {
            foreach ($headers as $colName) {
                $colName = strtoupper(trim($colName));

                if (!isset($dataset[$colName])) {
                    continue;
                }

                $value = $row[$colName] ?? null;

                if (is_numeric($value)) {
                    $dataset[$colName]['data'][] = (float) $value;
                } else {
                    $dataset[$colName]['data'][] = 0;
                }
            }
        }

        if (empty($dataset)) {
            $dataset = [
                'EMPTY' => [
                    'label' => 'DATA TIDAK DITEMUKAN',
                    'data' => [0]
                ]
            ];
        }

        // Pastikan file blade berada di resources/views/pages/variabel.blade.php
        return view('pages.variabel', [
            'dataset' => $dataset
        ]);
    }
}