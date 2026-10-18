<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SpasialController extends Controller
{
    public function index()
    {
        // =====================================================
        // 1. GEOJSON BASE (DESA GEOMETRY)
        // =====================================================
        $geojsonPath = public_path('data/Indeks_Entropy.geojson');

        if (!file_exists($geojsonPath)) {
            abort(404, 'File public/data/Indeks_Entropy.geojson tidak ditemukan');
        }

        $geojson = json_decode(file_get_contents($geojsonPath), true);

        if (!$geojson || !isset($geojson['features'])) {
            abort(500, 'GeoJSON Indeks_Entropy tidak valid');
        }

        // =====================================================
        // 2. NEIGHBOR (QUEEN CONTIGUITY)
        // =====================================================
        $neighborPath = public_path('data/neighbors.json');
        $neighbors = [];

        if (file_exists($neighborPath)) {
            $neighbors = json_decode(file_get_contents($neighborPath), true);
        }

        // =====================================================
        // 3. SPATIAL LAG DATA (MORAN SCATTERPLOT)
        // =====================================================
        $pathCsv = public_path('data/df_result_spatial_lag_BARU.csv');
        $scatterData = [];

        if (file_exists($pathCsv) && ($handle = fopen($pathCsv, "r")) !== FALSE) {
            
            // Baca header dengan delimiter ';'
            $rawHeader = fgetcsv($handle, 1000, ";");
            
            if ($rawHeader) {
                // Bersihkan UTF-8 BOM
                $rawHeader[0] = preg_replace('/^\xEF\xBB\xBF/', '', $rawHeader[0]);
                
                // Trim seluruh nama header
                $header = array_map(function($h) {
                    return trim($h);
                }, $rawHeader);
                
                while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                    if (count($header) == count($data)) {
                        $row = array_combine($header, $data);
                        
                        $scatterData[] = [
                            'nmdesa' => $row['nmdesa'] ?? ($row['DESA'] ?? 'Desa'),
                            'x'      => isset($row['Indeks_Entropy']) ? (float) $row['Indeks_Entropy'] : 0,
                            'y'      => isset($row['Spatial Lag']) ? (float) $row['Spatial Lag'] : 0,
                        ];
                    }
                }
            }
            fclose($handle);
        }

        // =====================================================
        // 4. RETURN VIEW
        // =====================================================
        return view('pages.spasial', [
            'scatterData' => $scatterData,
            'moranData'   => $geojson,
            'neighbors'   => $neighbors,
        ]);
    }
}