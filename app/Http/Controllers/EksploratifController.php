<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class EksploratifController extends Controller
{
    public function index()
    {
        // =============================================
        // PATH EXCEL (Menggunakan public_path)
        // =============================================

        // Jika file berada di: public/data/DTW.xlsx
        $path = public_path('data/DTW.xlsx');
        
        // Catatan: Jika file langsung berada di public/DTW.xlsx, gunakan:
        // $path = public_path('DTW.xlsx');

        // =============================================
        // LOAD EXCEL
        // =============================================

        $rows = Excel::toArray([], $path);

        $data = $rows[0];

        // =============================================
        // HEADER
        // =============================================

        $header = array_map(
            'trim',
            $data[0]
        );

        unset($data[0]);

        // =============================================
        // ARRAY DATA
        // =============================================

        $wisata = [];

        foreach ($data as $row) {

            $item = array_combine(
                $header,
                $row
            );

            // Skip jika kosong
            if (
                empty($item['LATITUDE']) ||
                empty($item['LONGITUDE'])
            ) {
                continue;
            }

            $wisata[] = [

                'NAMA' =>
                    $item['NAMA'] ?? '-',

                'KATEGORI' =>
                    $item['KATEGORI'] ?? '-',

                'DESA' =>
                    $item['DESA'] ?? '-',

                'KECAMATAN' =>
                    $item['KECAMATAN'] ?? '-',

                'LATITUDE' =>
                    (float)$item['LATITUDE'],

                'LONGITUDE' =>
                    (float)$item['LONGITUDE'],
                
                'TAUTAN' =>
                    $item['TAUTAN'] ?? null

            ];
        }

        // =============================================
        // LIST KATEGORI
        // =============================================

        $kategoriList = collect($wisata)

            ->pluck('KATEGORI')

            ->unique()

            ->values();

        // =============================================
        // ANN ANALYSIS DATA
        // =============================================

        $annData = [
            [
                'kategori' => 'Keseluruhan',
                'jumlah' => 336,
                'observed' => 1043.88,
                'expected' => 1569.8,
                'ann' => 0.665,
                'z' => -5.4005,
                'p' => 0,
                'pola' => 'Cenderung Mengelompok',
                'hasil' => 'Signifikan',
                'interpretasi' => 'Pola persebaran wisata mengelompok dan signifikan secara statistik.'
            ],
            [
                'kategori' => 'Wisata Tirta',
                'jumlah' => 38,
                'observed' => 1938.95,
                'expected' => 2145.76,
                'ann' => 0.9036,
                'z' => -1.1366,
                'p' => 0.255695,
                'pola' => 'Cenderung Mengelompok',
                'hasil' => 'Tidak Signifikan',
                'interpretasi' => 'Pola persebaran wisata tidak berbeda signifikan dari pola acak.'
            ],
            [
                'kategori' => 'Wisata Taman Rekreasi',
                'jumlah' => 58,
                'observed' => 1389.72,
                'expected' => 1736.84,
                'ann' => 0.8001,
                'z' => -2.9118,
                'p' => 0.003593,
                'pola' => 'Cenderung Mengelompok',
                'hasil' => 'Signifikan',
                'interpretasi' => 'Pola persebaran wisata mengelompok dan signifikan secara statistik.'
            ],
            [
                'kategori' => 'Wisata Budaya',
                'jumlah' => 109,
                'observed' => 1054.73,
                'expected' => 1266.95,
                'ann' => 0.8325,
                'z' => -3.3456,
                'p' => 0.000821,
                'pola' => 'Cenderung Mengelompok',
                'hasil' => 'Signifikan',
                'interpretasi' => 'Pola persebaran wisata mengelompok dan signifikan secara statistik.'
            ],
            [
                'kategori' => 'Wisata Buatan',
                'jumlah' => 60,
                'observed' => 1540.65,
                'expected' => 1707.64,
                'ann' => 0.9022,
                'z' => -1.4491,
                'p' => 0.147301,
                'pola' => 'Cenderung Mengelompok',
                'hasil' => 'Tidak Signifikan',
                'interpretasi' => 'Pola persebaran wisata tidak berbeda signifikan dari pola acak.'
            ],
            [
                'kategori' => 'Wisata Alam',
                'jumlah' => 71,
                'observed' => 1043.88,
                'expected' => 1569.8,
                'ann' => 0.665,
                'z' => -5.4005,
                'p' => 0,
                'pola' => 'Cenderung Mengelompok',
                'hasil' => 'Signifikan',
                'interpretasi' => 'Pola persebaran wisata mengelompok dan signifikan secara statistik.'
            ],
        ];

        // =============================================
        // RETURN VIEW
        // =============================================

        return view(
            'pages.eksploratif',
            [

                'wisata' => $wisata,

                'kategoriList' => $kategoriList,

                'jumlahWisata' => count($wisata),
                
                'annData' => $annData,
            ]
        );
    }
}