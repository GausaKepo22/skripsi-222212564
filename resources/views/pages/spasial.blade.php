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
                    Global Moran’s I
                </h2>

                <div class="space-y-4 text-sm text-gray-700">
                    <div>
                        <p class="font-semibold">Weighted</p>
                        <p>Queen Contiguity</p>
                    </div>

                    <div>
                        <p class="font-semibold">Moran’s I</p>
                        <p>0.319953</p>
                    </div>

                    <div>
                        <p class="font-semibold">Expected I</p>
                        <p>-0.002500</p>
                    </div>

                    <div>
                        <p class="font-semibold">Variance</p>
                        <p>0.000898</p>
                    </div>

                    <div>
                        <p class="font-semibold">Z-Score</p>
                        <p>10.763360</p>
                    </div>

                    <div>
                        <p class="font-semibold">P-Value</p>
                        <p>0.000000</p>
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
            <b>Desa:</b> ${feature.properties.DESA}<br>
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
        case 'High-High': return '#DC2626';
        case 'Low-Low': return '#2563EB';
        case 'High-Low': return '#F97316';
        case 'Low-High': return '#A855F7';
        default: return '#E5E7EB';
    }
}

// =====================================================
// 1. LOAD BASE GEOMETRY (INI WAJIB)
// =====================================================
fetch('/data/desa-klaten.geojson')
.then(res => res.json())
.then(baseGeo => {

    // =====================================================
    // 2. LOAD LISA DATA
    // =====================================================
    fetch('/data/Hasil_Moran_Indeks.geojson')
    .then(res => res.json())
    .then(lisaData => {

        // bikin index lookup LISA
        let lisaMap = {};
        lisaData.features.forEach(f => {

            let key = f.properties.nmdesa; // atau iddesa (lebih aman)
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
                        ? getLISAColor(lisa.LISA_Cluster)
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

                        <b>Desa:</b> ${feature.properties.nmdesa}<br>
                        <b>Kecamatan:</b> ${feature.properties.nmkec}<br>

                        <hr>

                        <b>Cluster:</b> ${lisa?.LISA_Cluster ?? '-'}<br>
                        <b>Indeks:</b> ${lisa?.Indeks_Entropy ?? '-'}<br>
                        <b>Z-Score:</b> ${lisa?.Z_Score ?? '-'}<br>
                        <b>P-Value:</b> ${lisa?.Local_p ?? '-'}
                    </div>
                `);
            }

        }).addTo(map2);

// =====================================================
// LEGEND LISA
// =====================================================

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
                <span style="background:#DC2626;width:12px;height:12px;display:inline-block;margin-right:8px;"></span>
                High-High (Hotspot)
            </div>

            <div style="display:flex; align-items:center; margin-bottom:4px;">
                <span style="background:#2563EB;width:12px;height:12px;display:inline-block;margin-right:8px;"></span>
                Low-Low (Coldspot)
            </div>

            <div style="display:flex; align-items:center; margin-bottom:4px;">
                <span style="background:#F97316;width:12px;height:12px;display:inline-block;margin-right:8px;"></span>
                High-Low (Outlier)
            </div>

            <div style="display:flex; align-items:center;">
                <span style="background:#A855F7;width:12px;height:12px;display:inline-block;margin-right:8px;"></span>
                Low-High (Outlier)
            </div>

        </div>
    `;

    return div;
};

legend.addTo(map2);

    });

});

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
                    'Indeks: %{x:.3f}<br>' +
                    'Spatial Lag: %{y:.3f}<extra></extra>',

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

            // ======================
            // ANNOTATION R DAN R²
            // ======================

            annotations: [

                {
                    x: xMid,
                    y: yMid,

                    text:
                        'r = ' +
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
                    'Indeks Entropy',

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
                    'Spatial Lag',

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