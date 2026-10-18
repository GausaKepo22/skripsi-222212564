@extends('layouts.app')

@section('title', 'Analisis Spasial Moran & LISA')

@section('content')

<!-- ===================================================== -->
<!-- HERO -->
<!-- ===================================================== -->

<section class="pt-28 pb-16 bg-gradient-to-r from-[#0F172A] via-[#1E3A8A] to-[#2563EB] text-white">

    <div class="max-w-7xl mx-auto px-6 text-center">

        <h1 class="text-4xl md:text-5xl font-extrabold mb-4">
            Analisis Spasial
        </h1>

        <p class="max-w-3xl mx-auto text-blue-100 text-lg leading-relaxed">
            Analisis autokorelasi spasial untuk melihat pola keterkaitan antar wilayah
            berdasarkan Indeks Entropy Desa di Kabupaten Klaten.
        </p>

    </div>

</section>

<!-- ===================================================== -->
<!-- MAP + STATISTIK -->
<!-- ===================================================== -->

<section class="py-12 bg-slate-100">

    <div class="max-w-7xl mx-auto px-6">

        <div class="grid lg:grid-cols-2 gap-6">

            <!-- ================= MAP ================= -->
            <div class="bg-white rounded-3xl shadow-xl overflow-hidden">

                <div class="p-5 border-b">
                    <h2 class="text-xl font-bold text-[#1A237E]">
                        Peta Ketetanggaan (Queen Contiguity)
                    </h2>
                </div>

                <div id="map" class="h-[550px]"></div>
            </div>

            <!-- ================= STATISTIK ================= -->
            <div class="bg-white rounded-3xl shadow-xl p-6">

                <h2 class="text-xl font-bold text-[#1A237E] mb-6 text-center">
                    Global Moran’s I <br> Hasil Uji Signifikansi Monte Carlo Permutation
                </h2>

                <div class="space-y-4 text-sm text-gray-700">
                    <div>
                        <p class="font-semibold">Weighted</p>
                        <p>Queen Contiguity</p>
                    </div>
                 	<div>
                        <p class="font-semibold">Jumlah Permutasi</p>
                        <p>999</p>
                    </div>
                    <div>
                        <p class="font-semibold">Moran’s I</p>
                        <p>0.3200</p>
                    </div>

                    <div>
                        <p class="font-semibold">Expected I</p>
                        <p>-0,0022</p>
                    </div>

                    <div>
                        <p class="font-semibold">Standard Deviation</p>
                        <p>0.0297</p>
                    </div>

                    <div>
                        <p class="font-semibold">Z-Score</p>
                        <p>10,8377</p>
                    </div>

                    <div>
                        <p class="font-semibold">P-Value</p>
                        <p>0.0010</p>
                    </div>

                    <hr>

                    <div>
                        <p class="font-semibold text-[#1A237E]">Interpretasi</p>
                        <p>
                            Terdapat autokorelasi spasial positif signifikan.
                            Pola wilayah bersifat <b>clustered</b>.
                        </p>
                    </div>

                </div>

                <!-- PENJELASAN QUEEN -->
                <div class="mt-6 bg-blue-50 p-4 rounded-xl text-sm text-gray-700">
                    <p class="font-bold text-[#1A237E] mb-2">Cara kerja Queen Contiguity</p>
                    <p>
                        Queen contiguity menganggap dua wilayah bertetangga jika
                        memiliki <b>shared boundary (sisi)</b> atau <b>shared vertex (titik sudut)</b>.
                        Dalam visualisasi ini, setiap hubungan tetangga dihubungkan dengan garis,
                        lalu diberi bobot sama (queen weighted / row-standardized).
                    </p>
                </div>

            </div>

        </div>

    </div>

</section>


<!-- ===================================================== -->
<!-- LISA MAP SECTION -->
<!-- ===================================================== -->

<section class="py-12 bg-white">

    <div class="max-w-7xl mx-auto px-6">

        <div class="bg-white rounded-3xl shadow-xl overflow-hidden">

            <!-- HEADER -->
            <div class="p-5 border-b">
                <h2 class="text-xl font-bold text-[#1A237E]">
                    Peta Klasifikasi LISA (Local Moran)
                </h2>

                <p class="text-sm text-gray-600 mt-1">
                    Visualisasi cluster spasial: High-High, Low-Low, High-Low, Low-High
                </p>
            </div>

            <!-- CONTENT FLEX -->
            <div class="grid lg:grid-cols-2 gap-4">

                <!-- MAP -->
                <div id="lisaMap" class="h-[500px]"></div>

                <!-- SCATTER -->
                <div class="bg-slate-50 rounded-xl p-3">
                    <div id="scatterPlot" class="h-[500px]"></div>
                </div>

            </div>

        </div>

    </div>

</section>


<!-- ===================================================== -->
<!-- TABEL POLA LISA -->
<!-- ===================================================== -->

<section class="py-12 bg-slate-100">

    <div class="max-w-7xl mx-auto px-6">

        <div class="bg-white rounded-3xl shadow-xl overflow-hidden">

            <!-- HEADER TABEL -->
            <div class="p-6 border-b">
                <h2 class="text-xl font-bold text-[#1A237E]">
                    Distribusi Pola LISA
                </h2>

                <p class="text-sm text-gray-600 mt-1">
                    Distribusi desa/kelurahan berdasarkan klasifikasi autokorelasi spasial lokal.
                </p>
            </div>

            <!-- TABEL -->
            <div class="overflow-x-auto">

                <table class="w-full text-sm text-left text-gray-700">

                    <thead class="bg-[#1A237E] text-white">

                        <tr>
                            <th class="px-6 py-4 font-semibold">
                                Pola LISA
                            </th>

                            <th class="px-6 py-4 font-semibold text-center">
                                Jumlah Desa/Kelurahan
                            </th>

                            <th class="px-6 py-4 font-semibold text-center">
                                Presentase (%)
                            </th>

                            <th class="px-6 py-4 font-semibold">
                                Interpretasi
                            </th>
                        </tr>

                    </thead>

                    <tbody class="divide-y divide-gray-200">

                        <!-- HIGH-HIGH -->
                        <tr class="hover:bg-green-50 transition">

                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">

                                    <span
                                        class="w-3 h-3 rounded-full bg-[#16A34A]">
                                    </span>

                                    <span class="font-semibold text-gray-800">
                                        High-High (HH)
                                    </span>

                                </div>
                            </td>

                            <td class="px-6 py-4 text-center font-medium">
                                36
                            </td>

                            <td class="px-6 py-4 text-center">
                                8,98
                            </td>

                            <td class="px-6 py-4">
                                Wilayah dengan IPPP tinggi dikelilingi wilayah dengan IPPP tinggi.
                            </td>

                        </tr>


                        <!-- HIGH-LOW -->
                        <tr class="hover:bg-blue-50 transition">

                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">

                                    <span
                                        class="w-3 h-3 rounded-full bg-[#2563EB]">
                                    </span>

                                    <span class="font-semibold text-gray-800">
                                        High-Low (HL)
                                    </span>

                                </div>
                            </td>

                            <td class="px-6 py-4 text-center font-medium">
                                8
                            </td>

                            <td class="px-6 py-4 text-center">
                                1,99
                            </td>

                            <td class="px-6 py-4">
                                Wilayah dengan IPPP tinggi dikelilingi wilayah dengan IPPP rendah.
                            </td>

                        </tr>


                        <!-- LOW-HIGH -->
                        <tr class="hover:bg-yellow-50 transition">

                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">

                                    <span
                                        class="w-3 h-3 rounded-full bg-[#FACC15]">
                                    </span>

                                    <span class="font-semibold text-gray-800">
                                        Low-High (LH)
                                    </span>

                                </div>
                            </td>

                            <td class="px-6 py-4 text-center font-medium">
                                6
                            </td>

                            <td class="px-6 py-4 text-center">
                                1,49
                            </td>

                            <td class="px-6 py-4">
                                Wilayah dengan IPPP rendah dikelilingi wilayah dengan IPPP tinggi.
                            </td>

                        </tr>


                        <!-- LOW-LOW -->
                        <tr class="hover:bg-red-50 transition">

                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">

                                    <span
                                        class="w-3 h-3 rounded-full bg-[#DC2626]">
                                    </span>

                                    <span class="font-semibold text-gray-800">
                                        Low-Low (LL)
                                    </span>

                                </div>
                            </td>

                            <td class="px-6 py-4 text-center font-medium">
                                23
                            </td>

                            <td class="px-6 py-4 text-center">
                                5,74
                            </td>

                            <td class="px-6 py-4">
                                Wilayah dengan IPPP rendah dikelilingi wilayah dengan IPPP rendah.
                            </td>

                        </tr>


                        <!-- NOT SIGNIFICANT -->
                        <tr class="hover:bg-gray-50 transition">

                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">

                                    <span
                                        class="w-3 h-3 rounded-full bg-[#E5E7EB] border border-gray-400">
                                    </span>

                                    <span class="font-semibold text-gray-800">
                                        Not Significant (NS)
                                    </span>

                                </div>
                            </td>

                            <td class="px-6 py-4 text-center font-medium">
                                328
                            </td>

                            <td class="px-6 py-4 text-center">
                                81,80
                            </td>

                            <td class="px-6 py-4">
                                Tidak signifikan secara lokal.
                            </td>

                        </tr>


                        <!-- TOTAL -->
                        <tr class="bg-blue-50 font-bold text-[#1A237E]">

                            <td class="px-6 py-4">
                                Total
                            </td>

                            <td class="px-6 py-4 text-center">
                                401
                            </td>

                            <td class="px-6 py-4 text-center">
                                100,00
                            </td>

                            <td class="px-6 py-4">
                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</section>

@endsection

<!-- ===================================================== -->
<!-- STYLE -->
<!-- ===================================================== -->

@push('styles')

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

<style>
    .leaflet-container {
        background: white !important;
    }
</style>

@endpush

<!-- ===================================================== -->
<!-- SCRIPT -->
<!-- ===================================================== -->

@push('scripts')

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdn.plot.ly/plotly-2.30.0.min.js"></script>

<script>

const map1 = L.map('map').setView([-7.705, 110.606], 11);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap'
}).addTo(map1);

let moranData = @json($moranData);
let neighbors = @json($neighbors);

let lineLayer = L.layerGroup().addTo(map1);
let centroids = [];

L.geoJSON(moranData, {
    style: () => ({
        color: '#374151',
        weight: 0.8,
        fillColor: '#e5e7eb',
        fillOpacity: 0.75
    }),
    onEachFeature: (feature, layer) => {
        let center = layer.getBounds().getCenter();
        centroids.push(center);

        layer.bindPopup(`
            <b>Desa:</b> ${feature.properties.Desa}<br>
            <b>Indeks:</b> ${feature.properties.Indeks}
        `);
    }
}).addTo(map1);

function drawNeighbors() {

    lineLayer.clearLayers();

    let drawn = new Set();

    Object.keys(neighbors).forEach(i => {
        neighbors[i].forEach(j => {

            let key = [i, j].sort().join('-');
            if (drawn.has(key)) return;
            drawn.add(key);

            if (!centroids[i] || !centroids[j]) return;

            L.polyline(
                [centroids[i], centroids[j]],
                {
                    color: 'red',
                    weight: 2.5,
                    opacity: 0.7
                }
            ).addTo(lineLayer);

        });
    });
}

setTimeout(drawNeighbors, 800);

</script>

<script>

const map2 = L.map('lisaMap').setView([-7.705, 110.606], 11);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap'
}).addTo(map2);

function getLISAColor(cluster) {
    switch (cluster) {
        case 'High-High': return '#16A34A'; // Hijau
        case 'Low-Low': return '#DC2626';   // Merah
        case 'High-Low': return '#2563EB';  // Biru
        case 'Low-High': return '#FACC15';  // Kuning
        default: return '#E5E7EB';
    }
}

// =====================================================
// 1. LOAD BASE GEOMETRY (MENGGUNAKAN HELPER ASSET)
// =====================================================
fetch("{{ asset('data/desa-klaten.geojson') }}")
.then(res => {
    if (!res.ok) throw new Error('Gagal memuat desa-klaten.geojson');
    return res.json();
})
.then(baseGeo => {

    // =====================================================
    // 2. LOAD LISA DATA (MENGGUNAKAN HELPER ASSET)
    // =====================================================
    fetch("{{ asset('data/Lisa-MonteCarlo.geojson') }}")
    .then(res => {
        if (!res.ok) throw new Error('Gagal memuat Lisa-MonteCarlo.geojson');
        return res.json();
    })
    .then(lisaData => {

        let lisaMap = {};
        lisaData.features.forEach(f => {
            let key = f.properties.nmdesa;
            lisaMap[key] = f.properties;
        });

        // =====================================================
        // 3. MERGE BASE + LISA
        // =====================================================
        L.geoJSON(baseGeo, {
            style: function (feature) {
                let key = feature.properties.nmdesa;
                let lisa = lisaMap[key];

                return {
                    fillColor: lisa
                        ? getLISAColor(lisa.Klaster_LISA_Final)
                        : '#E5E7EB',
                    color: '#374151',
                    weight: 0.6,
                    fillOpacity: 0.75
                };
            },

            onEachFeature: function (feature, layer) {
                let key = feature.properties.nmdesa;
                let lisa = lisaMap[key];

                layer.bindPopup(`
                    <div style="font-size:13px">
                        <b>Desa:</b> ${feature.properties.nmdesa ?? '-'}<br>
                        <b>Kecamatan:</b> ${feature.properties.nmkec ?? '-'}<br>
                        <hr>
                        <b>Cluster:</b> ${lisa?.Klaster_LISA_Final ?? '-'}<br>
                        <b>Indeks:</b> ${lisa?.Nilai_Lokal_I ?? '-'}<br>
                        <b>Z-Score:</b> ${lisa?.Z_Score ?? '-'}<br>
                        <b>Pseudo P-Value:</b> ${lisa?.Pseudo_P_Value ?? '-'}
                    </div>
                `);
            }
        }).addTo(map2);

        // LEGEND LISA
        const legend = L.control({ position: 'bottomright' });
        legend.onAdd = function () {
            const div = L.DomUtil.create('div', 'info legend');
            div.innerHTML = `
                <div style="
                    background:white;
                    padding:12px 14px;
                    border-radius:12px;
                    box-shadow:0 2px 10px rgba(0,0,0,0.2);
                    font-size:12px;
                    line-height:18px;
                ">
                    <b style="color:#1A237E;">LISA Cluster</b><br><br>
                    <div style="display:flex; align-items:center; margin-bottom:4px;">
                        <span style="background:#16A34A;width:12px;height:12px;display:inline-block;margin-right:8px;"></span>
                        High-High (Hotspot)
                    </div>
                    <div style="display:flex; align-items:center; margin-bottom:4px;">
                        <span style="background:#DC2626;width:12px;height:12px;display:inline-block;margin-right:8px;"></span>
                        Low-Low (Coldspot)
                    </div>
                    <div style="display:flex; align-items:center; margin-bottom:4px;">
                        <span style="background:#2563EB;width:12px;height:12px;display:inline-block;margin-right:8px;"></span>
                        High-Low (Outlier)
                    </div>
                    <div style="display:flex; align-items:center;">
                        <span style="background:#FACC15;width:12px;height:12px;display:inline-block;margin-right:8px;"></span>
                        Low-High (Outlier)
                    </div>
                </div>
            `;
            return div;
        };
        legend.addTo(map2);

    })
    .catch(err => console.error("Error LISA:", err));

})
.catch(err => console.error("Error Base GeoJSON:", err));

let scatterData = @json($scatterData);

drawScatter(scatterData);

function drawScatter(data) {

    if (!data || data.length === 0) {
        console.error("Scatter data kosong");
        return;
    }

    let x = data.map(d => Number(d.x));
    let y = data.map(d => Number(d.y));

    // =====================================================
    // KORELASI PEARSON
    // =====================================================

    function pearsonCorrelation(x, y) {

        const n = x.length;

        const meanX =
            x.reduce((a,b)=>a+b,0) / n;

        const meanY =
            y.reduce((a,b)=>a+b,0) / n;

        let numerator = 0;
        let denomX = 0;
        let denomY = 0;

        for(let i=0;i<n;i++){

            const dx = x[i] - meanX;
            const dy = y[i] - meanY;

            numerator += dx * dy;
            denomX += dx * dx;
            denomY += dy * dy;
        }

        return numerator /
            Math.sqrt(denomX * denomY);
    }

    // =====================================================
    // REGRESI LINEAR OLS
    // =====================================================

    function linearRegression(x, y) {

        let n = x.length;

        let sumX = 0;
        let sumY = 0;
        let sumXY = 0;
        let sumXX = 0;

        for (let i = 0; i < n; i++) {

            sumX += x[i];
            sumY += y[i];
            sumXY += x[i] * y[i];
            sumXX += x[i] * x[i];
        }

        let b =
            (n * sumXY - sumX * sumY)
            /
            (n * sumXX - sumX * sumX);

        let a =
            (sumY / n)
            -
            b * (sumX / n);

        return { a, b };
    }

    const reg =
        linearRegression(x, y);

    const corr =
        pearsonCorrelation(x, y);

    const r2 =
        corr * corr;

    const xMin =
        Math.min(...x);

    const xMax =
        Math.max(...x);

    const xMid =
        (xMin + xMax) / 2;

    const yMid =
        reg.a + reg.b * xMid;

    const xLine =
        [xMin, xMax];

    const yLine =
        xLine.map(v => reg.a + reg.b * v);

    // =====================================================
    // PLOT
    // =====================================================

    Plotly.newPlot(

        'scatterPlot',

        [
            // ======================
            // TITIK DESA
            // ======================

            {
                x: x,
                y: y,

                mode: 'markers',
                type: 'scatter',

                text:
                    data.map(d => d.nmdesa),

                hovertemplate:
                    '<b>%{text}</b><br>' +
                    'IPP Standarized: %{x:.3f}<br>' +
                    'Spatial Lag Standarized IPP: %{y:.3f}<extra></extra>',

                marker: {
                    size: 8,
                    color: 'rgb(26, 35, 126)',
                    opacity: 0.8
                },

                name: 'Desa'
            },

            // ======================
            // GARIS REGRESI
            // ======================

            {
                x: xLine,
                y: yLine,

                mode: 'lines',
                type: 'scatter',

                line: {
                    color: '#dc2626',
                    width: 3
                },

                hoverinfo: 'skip',

                name: 'Garis Regresi'
            }

        ],

        {

            title: {
                text: '<b>Moran Scatterplot</b>',
                font: {
                    size: 18,
                    color: '#1A237E'
                }
            },

            // =====================================================
            // GARIS PEMBATAS KUADRAN x = 0 DAN y = 0
            // =====================================================

            shapes: [

                // GARIS VERTIKAL x = 0
                {
                    type: 'line',
                    x0: 0,
                    x1: 0,
                    y0: 0,
                    y1: 1,
                    yref: 'paper',

                    line: {
                        color: '#111827',
                        width: 3
                    },

                    layer: 'above'
                },

                // GARIS HORIZONTAL y = 0
                {
                    type: 'line',
                    x0: 0,
                    x1: 1,
                    xref: 'paper',
                    y0: 0,
                    y1: 0,

                    line: {
                        color: '#111827',
                        width: 3
                    },

                    layer: 'above'
                }

            ],

            // =======================
            // ANNOTATION R DAN R²
            // =======================

            annotations: [

                {
                    x: xMid,
                    y: yMid,

                    text:
                    	'Moran\'s I = 0.3200' +
                        '<br>r = ' +
                        corr.toFixed(3)
                        +
                        '<br>R² = ' +
                        r2.toFixed(3),

                    showarrow: false,

                    bgcolor:
                        'rgba(255,255,255,0.90)',

                    bordercolor:
                        '#000',

                    borderwidth: 1,

                    borderpad: 6,

                    font: {
                        size: 12,
                        color: '#000'
                    }
                }

            ],

            xaxis: {

                title:
                    'Indeks Potensi Pengembangan Pariwisata (IPPP) Standarized',

                showgrid: true,

                gridcolor:
                    '#E5E7EB',

                zeroline: false,

                showline: true,

                linecolor:
                    '#CBD5E1',

                mirror: true
            },

            yaxis: {

                title:
                    'Spatial Lag Standarized IPP ',

                showgrid: true,

                gridcolor:
                    '#E5E7EB',

                zeroline: false,

                showline: true,

                linecolor:
                    '#CBD5E1',

                mirror: true
            },

            plot_bgcolor:
                '#eff6ff',

            paper_bgcolor:
                '#FFFFFF',

            hoverlabel: {

                bgcolor:
                    '#000000',

                bordercolor:
                    '#FFFFFF',

                font: {
                    color: '#FFFFFF'
                }
            },

            legend: {

                orientation: 'h',

                x: 0.5,

                xanchor: 'center',

                y: -0.22
            },

            margin: {

                t: 60,

                b: 90,

                l: 70,

                r: 40
            }

        },

        {
            responsive: true
        }

    );
}

</script>

@endpush