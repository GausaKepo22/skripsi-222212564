<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class SpasialController extends Controller
{
    public function index()
    {
        // =====================================================
        // 1. GEOJSON BASE (DESA GEOMETRY)
        // =====================================================

        $geojsonPath = public_path('data/Indeks_Entropy.geojson');

        if (!file_exists($geojsonPath)) {
            abort(404, 'GeoJSON tidak ditemukan');
        }

        $geojson = json_decode(file_get_contents($geojsonPath), true);

        if (!$geojson || !isset($geojson['features'])) {
            abort(500, 'GeoJSON tidak valid');
        }

        // =====================================================
        // 2. NEIGHBOR (QUEEN CONTIGUITY - FROM PYTHON)
        // =====================================================

        $neighborPath = public_path('data/neighbors.json');

        $neighbors = [];

        if (file_exists($neighborPath)) {
            $neighbors = json_decode(file_get_contents($neighborPath), true);
        }

        // =====================================================
        // 3. SPATIAL LAG DATA (MORAN SCATTERPLOT)
        // =====================================================

        $excelPath = public_path('data/df_result_spatial_lag.xlsx');

        $scatterData = [];

        if (file_exists($excelPath)) {

            $rows = Excel::toArray([], $excelPath)[0];

            $header = array_map('trim', $rows[0]);
            unset($rows[0]);

            foreach ($rows as $row) {

                $item = array_combine($header, $row);

                if (!isset($item['Indeks_Entropy']) || !isset($item['Spatial Lag'])) {
                    continue;
                }

                $scatterData[] = [
                    'nmkec' => $item['nmkec'] ?? '',
                    'nmdesa' => $item['nmdesa'] ?? '',
                    'x' => (float) $item['Indeks_Entropy'],
                    'y' => (float) $item['Spatial Lag'],
                ];
            }
        }

        // =====================================================
        // 4. RETURN VIEW
        // =====================================================

        return view('pages.spasial', [
            'moranData'   => $geojson,
            'neighbors'   => $neighbors,
            'scatterData' => $scatterData
        ]);
    }
}