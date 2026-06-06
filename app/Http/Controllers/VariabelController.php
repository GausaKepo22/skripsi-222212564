<?php

namespace App\Http\Controllers;

use PhpOffice\PhpSpreadsheet\IOFactory;

class VariabelController extends Controller
{
    public function index()
    {
        $filePath = public_path('data/variabel.xlsx');

        if (!file_exists($filePath)) {
            abort(404, 'File Excel tidak ditemukan');
        }

        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray(null, true, true, true);

        // =====================================================
        // HEADER
        // =====================================================
        $headers = array_map(function ($h) {
            return strtoupper(trim($h));
        }, $rows[1]);

        unset($rows[1]);

        // =====================================================
        // KOLOM YANG DIPAKAI
        // =====================================================
        $allowed = [
            'RESTORAN',
            'BANK',
            'MENARA TELEPON',
            'OPERATOR SELULER',
            'ATM',
            'DTW_STASIUN',
            'MEAN_WR',
            'PERTOKOAN',
            'MINIMARKET',
            'KEDAI_MAKAN_MINUM',
            'HOTEL',
            'PENGINAPAN',
            'TOKO/WARUNG',
            'ALAM',
            'BUATAN',
            'BUDAYA',
            'REKREASI',
            'TIRTA'
        ];

        // =====================================================
        // NORMALISASI ALLOWED
        // =====================================================
        $allowed = array_map(function ($a) {
            return strtoupper(trim($a));
        }, $allowed);

        // =====================================================
        // LABEL UI
        // =====================================================
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

        // =====================================================
        // INIT DATASET
        // =====================================================
        $dataset = [];

        foreach ($headers as $key => $colName) {

            $colName = strtoupper(trim($colName));

            if (in_array($colName, $allowed)) {
                $dataset[$colName] = [
                    'label' => $labels[$colName] ?? $colName,
                    'data' => []
                ];
            }
        }

        // =====================================================
        // FILL DATASET
        // =====================================================
        foreach ($rows as $row) {

            foreach ($headers as $key => $colName) {

                $colName = strtoupper(trim($colName));

                if (!isset($dataset[$colName])) {
                    continue;
                }

                $value = $row[$key] ?? null;

                if (is_numeric($value)) {
                    $dataset[$colName]['data'][] = (float) $value;
                }
            }
        }

        // =====================================================
        // SAFETY CHECK (ANTI ERROR JS)
        // =====================================================
        if (empty($dataset)) {
            $dataset = [
                'EMPTY' => [
                    'label' => 'DATA TIDAK DITEMUKAN',
                    'data' => [0]
                ]
            ];
        }

        return view('pages.variabel', [
            'dataset' => $dataset ?? []
        ]);
    }
}