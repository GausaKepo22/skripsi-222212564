@extends('layouts.app')

@section('title', 'Variabel')

@section('content')

<!-- ===================================================== -->
<!-- HERO -->
<!-- ===================================================== -->

<section class="pt-28 pb-16 bg-gradient-to-r from-[#0F172A] via-[#1E3A8A] to-[#2563EB] text-white">

    <div class="max-w-7xl mx-auto px-6 text-center">

        <h1 class="text-4xl md:text-5xl font-extrabold mb-4">
            Analisis Deskriptif
        </h1>

        <p class="max-w-3xl mx-auto text-blue-100 text-lg leading-relaxed">
            Analisis deskriptif dilakukan terhadap variabel-variabel penelitian 
            untuk memahami karakteristik dari data dan hubungan antar variabel.
            Hasil dari analisis ini memberikan wawasan awal yang penting sebelum 
            melanjutkan ke tahap pembentukan indeks dan analisis spasial.
        </p>

    </div>

</section>

<!-- ===================================================== -->
<!-- TABEL INFORMASI VARIABEL -->
<!-- ===================================================== -->

<section class="py-12 bg-white">

    <div class="max-w-7xl mx-auto px-6">

        <div class="bg-white rounded-3xl shadow-xl p-6 border">

            <h2 class="text-3xl font-bold text-[#1A237E] text-center mb-8">
                Tabel Informasi Indikator Penelitian
            </h2>

            <div class="overflow-x-auto rounded-3xl border border-slate-200 shadow-md">

                <table class="min-w-full text-base text-center border-collapse overflow-hidden">

                    <thead>
                        <tr class="bg-[#1E3A8A] text-white text-lg">
                            <th class="p-4 border">No</th>
                            <th class="p-4 border">Dimensi</th>
                            <th class="p-4 border">Indikator</th>
                            <th class="p-4 border">Sumber Data</th>
                        </tr>
                    </thead>

                    <tbody class="font-medium">

                        <!-- ================= ATMOSPHERE 1: Attraction ================= -->
                        <tr class="bg-blue-50 border-b">
                            <td class="p-4">1</td>
                            <td class="p-4 font-bold text-blue-700">Attraction</td>
                            <td class="p-4">Jumlah Wisata Alam</td>
                            <td class="p-4">POI (Google Maps)</td>
                        </tr>
                        <tr class="bg-blue-50 border-b">
                            <td class="p-4">2</td>
                            <td class="p-4 font-bold text-blue-700">Attraction</td>
                            <td class="p-4">Jumlah Wisata Buatan</td>
                            <td class="p-4">POI (Google Maps)</td>
                        </tr>
                        <tr class="bg-blue-50 border-b">
                            <td class="p-4">3</td>
                            <td class="p-4 font-bold text-blue-700">Attraction</td>
                            <td class="p-4">Jumlah Wisata Budaya</td>
                            <td class="p-4">POI (Google Maps)</td>
                        </tr>
                        <tr class="bg-blue-50 border-b">
                            <td class="p-4">4</td>
                            <td class="p-4 font-bold text-blue-700">Attraction</td>
                            <td class="p-4">Jumlah Wisata Taman Rekreasi</td>
                            <td class="p-4">POI (Google Maps)</td>
                        </tr>
                        <tr class="bg-blue-50 border-b">
                            <td class="p-4">5</td>
                            <td class="p-4 font-bold text-blue-700">Attraction</td>
                            <td class="p-4">Jumlah Wisata Tirta</td>
                            <td class="p-4">POI (Google Maps)</td>
                        </tr>
                        <tr class="bg-blue-50 border-b">
                            <td class="p-4">6</td>
                            <td class="p-4 font-bold text-blue-700">Attraction</td>
                            <td class="p-4">Rata-rata Weighting Rate (WR)</td>
                            <td class="p-4">POI (Google Maps)</td>
                        </tr>

                        <!-- ================= ACCESSIBILITY ================= -->
                        <tr class="bg-green-50 border-b">
                            <td class="p-4">7</td>
                            <td class="p-4 font-bold text-green-700">Accessibility</td>
                            <td class="p-4">Rata-rata Jarak DTW ke Stasiun terdekat (km)</td>
                            <td class="p-4">Jaringan Jalan (Open Street Map)</td>
                        </tr>

                        <!-- ================= AMENITIES ================= -->
                        <tr class="bg-yellow-50 border-b">
                            <td class="p-4">8</td>
                            <td class="p-4 font-bold text-yellow-700">Amenities</td>
                            <td class="p-4">Jumlah Restoran</td>
                            <td class="p-4">Data Podes BPS</td>
                        </tr>
                        <tr class="bg-yellow-50 border-b">
                            <td class="p-4">9</td>
                            <td class="p-4 font-bold text-yellow-700">Amenities</td>
                            <td class="p-4">Jumlah Kedai Makan & Minum</td>
                            <td class="p-4">Data Podes BPS</td>
                        </tr>
                        <tr class="bg-yellow-50 border-b">
                            <td class="p-4">10</td>
                            <td class="p-4 font-bold text-yellow-700">Amenities</td>
                            <td class="p-4">Jumlah Hotel</td>
                            <td class="p-4">Data Podes BPS</td>
                        </tr>
                        <tr class="bg-yellow-50 border-b">
                            <td class="p-4">11</td>
                            <td class="p-4 font-bold text-yellow-700">Amenities</td>
                            <td class="p-4">Jumlah Penginapan</td>
                            <td class="p-4">Data Podes BPS</td>
                        </tr>
                        <tr class="bg-yellow-50 border-b">
                            <td class="p-4">12</td>
                            <td class="p-4 font-bold text-yellow-700">Amenities</td>
                            <td class="p-4">Jumlah Pertokoan</td>
                            <td class="p-4">Data Podes BPS</td>
                        </tr>
                        <tr class="bg-yellow-50 border-b">
                            <td class="p-4">13</td>
                            <td class="p-4 font-bold text-yellow-700">Amenities</td>
                            <td class="p-4">Jumlah Minimarket</td>
                            <td class="p-4">Data Podes BPS</td>
                        </tr>
                        <tr class="bg-yellow-50 border-b">
                            <td class="p-4">14</td>
                            <td class="p-4 font-bold text-yellow-700">Amenities</td>
                            <td class="p-4">Jumlah Toko/Warung</td>
                            <td class="p-4">Data Podes BPS</td>
                        </tr>

                        <!-- ================= ANCILLARY ================= -->
                        <tr class="bg-purple-50 border-b">
                            <td class="p-4">15</td>
                            <td class="p-4 font-bold text-purple-700">Ancillary Services</td>
                            <td class="p-4">Jumlah Bank</td>
                            <td class="p-4">Data Podes BPS</td>
                        </tr>
                        <tr class="bg-purple-50 border-b">
                            <td class="p-4">16</td>
                            <td class="p-4 font-bold text-purple-700">Ancillary Services</td>
                            <td class="p-4">Jumlah ATM/BRILink</td>
                            <td class="p-4">POI (Google Maps)</td>
                        </tr>
                        <tr class="bg-purple-50 border-b">
                            <td class="p-4">17</td>
                            <td class="p-4 font-bold text-purple-700">Ancillary Services</td>
                            <td class="p-4">Jumlah Menara Telepon</td>
                            <td class="p-4">Data Podes BPS</td>
                        </tr>
                        <tr class="bg-purple-50">
                            <td class="p-4">18</td>
                            <td class="p-4 font-bold text-purple-700">Ancillary Services</td>
                            <td class="p-4">Jumlah Operator Seluler</td>
                            <td class="p-4">Data Podes BPS</td>
                        </tr>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</section>

<!-- ===================================================== -->
<!-- AKSES DATASET -->
<!-- ===================================================== -->

<section class="py-12 bg-slate-50">

    <div class="max-w-7xl mx-auto px-6">

        <div class="bg-blue-50 e rounded-3xl shadow-xl p-10">

            <h2 class="text-3xl font-bold text-[#1A237E] text-center mb-8">
                Akses Dataset Penelitian
            </h2>

            <p class="max-w-3xl mx-auto text-[#010421] text-lg leading-relaxed mb-8">
                Dataset yang digunakan dalam penelitian ini dapat diunduh melalui fitur di bawah ini untuk
                keperluan akademik, reproduksi analisis, dan validasi hasil penelitian.
            </p>

            <div class="flex flex-wrap justify-center gap-4">

                <!-- EXCEL -->
                <a
                    href="{{ asset('data/dataset.xlsx') }}"
                    download
                    class="inline-flex items-center gap-3 px-8 py-4 bg-[#1E3A8A] text-white font-bold rounded-2xl shadow-lg hover:scale-105 transition"
                >
                    📊 Unduh Excel (.xlsx)
                </a>

                <!-- CSV -->
                <a
                    href="{{ asset('data/dataset.csv') }}"
                    download
                    class="inline-flex items-center gap-3 px-8 py-4 bg-slate-900 text-white font-bold rounded-2xl shadow-lg hover:scale-105 transition"
                >
                    📄 Unduh CSV (.csv)
                </a>

            </div>

        </div>

    </div>

</section>

<!-- ===================================================== -->
<!-- CORRELATION MATRIX -->
<!-- ===================================================== -->

<section class="py-12 bg-white">

    <div class="max-w-7xl mx-auto px-6">

        <div class="bg-white rounded-3xl shadow-xl p-6 border">

            <h2 class="text-3xl font-bold text-[#1A237E] text-center mb-8">
                Matriks Korelasi Pearson
            </h2>

<div class="overflow-x-auto">
    <div class="flex items-center gap-6" style="min-width:1100px;">

        <div style="height:950px; width:950px;">
            <canvas id="correlationChart"></canvas>
        </div>
        <!-- COLOR LEGEND -->
        <div class="flex items-center gap-3">

            <!-- Stick -->
            <div
                style="
                    width:28px;
                    height:500px;
                    border-radius:12px;
                    background: linear-gradient(
                        to top,
                        rgb(173,216,230),
                        rgb(180,0,40)
                    );
                    border:1px solid #d1d5db;
                ">
            </div>

            <!-- Label -->
            <div
                style="
                    height:500px;
                    display:flex;
                    flex-direction:column;
                    justify-content:space-between;
                    font-size:12px;
                    color:#4b5563;
                    font-weight:700;
                ">
                <span>1.0</span>
                <span>0.8</span>
                <span>0.6</span>
                <span>0.4</span>
                <span>0.2</span>
                <span>0.0</span>
            </div>

        </div>

        <div class="text-center mt-3">
            <span class="text-sm font-semibold text-gray-700">
                Kekuatan Korelasi (|r|)
            </span>
        </div>

    </div>
</div>

            <p class="text-sm text-gray-500 text-center">
                Korelasi Pearson antar variabel numerik (-1 sampai 1)
            </p>

        </div>

    </div>

</section>

<section class="py-12 bg-slate-100">
    <h2 class="text-3xl font-bold text-[#1A237E] text-center mb-8">
            Distribusi data dan Statistik Deskriptif
    </h2>

    <div class="max-w-7xl mx-auto px-6">

<div class="relative mb-8">

    <select
        id="variableSelector"
        class="w-full appearance-none
               px-6 py-5 pr-16
               rounded-3xl
               border border-slate-200
               shadow-xl
               bg-white
               text-lg font-semibold text-slate-500
               focus:ring-4 focus:ring-blue-200"
    >
        <option value="">
            📊 Pilih indikator yang ingin ditampilkan
        </option>
    </select>

    <div class="absolute inset-y-0 right-6 flex items-center pointer-events-none">
        <svg xmlns="http://www.w3.org/2000/svg"
             class="w-6 h-6 text-slate-500"
             fill="none"
             viewBox="0 0 24 24"
             stroke="currentColor">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M19 9l-7 7-7-7"/>
        </svg>
    </div>

</div>
        <!-- HASIL -->
        <div id="container"></div>

    </div>

</section>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-chart-matrix@2.0.1/dist/chartjs-chart-matrix.min.js"></script>

<script>
const dataset = {!! json_encode($dataset ?? [], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!};

// ===============================
// DESKRIPTIF (TIDAK DIUBAH)
// ===============================
function descriptiveStats(data) {
    const n = data.length;
    const mean = data.reduce((a, b) => a + b, 0) / n;

    const sorted = [...data].sort((a, b) => a - b);

    const median = n % 2 === 0
        ? (sorted[n / 2 - 1] + sorted[n / 2]) / 2
        : sorted[Math.floor(n / 2)];

    const min = Math.min(...data);
    const max = Math.max(...data);

    const variance = data.reduce((a, b) => Math.pow(b - mean, 2) + a, 0) / n;
    const std = Math.sqrt(variance);

    const freqMap = {};
    data.forEach(v => freqMap[v] = (freqMap[v] || 0) + 1);

    let mode = [];
    let maxFreq = 0;

    for (let k in freqMap) {
        if (freqMap[k] > maxFreq) {
            maxFreq = freqMap[k];
            mode = [Number(k)];
        } else if (freqMap[k] === maxFreq) {
            mode.push(Number(k));
        }
    }

    const q1 = quantile(sorted, 0.25);
    const q2 = median;
    const q3 = quantile(sorted, 0.75);

    return { mean, median, min, max, std, mode, q1, q2, q3 };
}

function quantile(sorted, q) {
    const pos = (sorted.length - 1) * q;
    const base = Math.floor(pos);
    const rest = pos - base;

    return sorted[base + 1] !== undefined
        ? sorted[base] + rest * (sorted[base + 1] - sorted[base])
        : sorted[base];
}

function createHistogram(data, bins = 10) {
    const min = Math.min(...data);
    const max = Math.max(...data);

    if (min === max) {
        return { labels: [`${min}`], values: [data.length] };
    }

    const binSize = (max - min) / bins;
    let hist = Array(bins).fill(0);

    data.forEach(v => {
        let i = Math.floor((v - min) / binSize);
        if (i >= bins) i = bins - 1;
        hist[i]++;
    });

    let labels = [];
    for (let i = 0; i < bins; i++) {
        let start = min + i * binSize;
        let end = min + (i + 1) * binSize;
        labels.push(`${start.toFixed(0)} - ${end.toFixed(0)}`);
    }

    return { labels, values: hist };
}

function smooth(data) {
    return data.map((v, i, arr) => {
        const p = arr[i - 1] ?? v;
        const n = arr[i + 1] ?? v;
        return (p + v + n) / 3;
    });
}

const correlationData = [
[1,0.362199,0.200036,0.022889,0.404133,0.058595,0.107174,0.397952,0.381538,0.240746,0.329400,0.222332,0.239717,0.025894,0.110746,0.064425,0.099526,0.086577],
[0.362199,1,0.220581,0.032143,0.575496,0.030315,0.079637,0.539080,0.379309,0.338552,0.255700,0.084575,0.194446,-0.060199,0.157577,0.082277,0.110194,0.027824],
[0.200036,0.220581,1,0.106081,0.207948,0.169369,0.181277,0.189805,0.235997,0.193912,0.178439,0.178329,0.160421,0.053183,0.057680,0.143760,0.085660,0.091187],
[0.022889,0.032143,0.106081,1,0.011093,-0.015430,0.006453,-0.004260,-0.005029,0.068172,0.016077,0.071669,0.054474,-0.011125,-0.049618,0.032321,0.092029,-0.055235],
[0.404133,0.575496,0.207948,0.011093,1,0.073323,0.110728,0.433844,0.436366,0.301135,0.212985,0.219433,0.192031,0.069685,0.105231,0.100752,0.063555,0.043708],
[0.058595,0.030315,0.169369,-0.015430,0.073323,1,0.969315,0.000973,0.047569,0.050607,0.031153,0.143477,0.006406,0.438307,0.392460,0.525983,0.383006,0.290338],
[0.107174,0.079637,0.181277,0.006453,0.110728,0.969315,1,0.054961,0.086449,0.105859,0.089260,0.158510,0.039161,0.382785,0.425796,0.552621,0.374938,0.335203],
[0.397952,0.539080,0.189805,-0.004260,0.433844,0.000973,0.054961,1,0.401940,0.296743,0.260038,0.215729,0.262920,-0.029749,0.099039,0.059861,0.133993,0.051964],
[0.381538,0.379309,0.235997,-0.005029,0.436366,0.047569,0.086449,0.401940,1,0.296605,0.280846,0.196935,0.161861,0.021661,0.037930,0.044064,0.060002,0.075110],
[0.240746,0.338552,0.193912,0.068172,0.301135,0.050607,0.105859,0.296743,0.296605,1,0.189144,0.045861,0.232779,0.015888,0.107157,0.029134,0.136760,0.094356],
[0.329400,0.255700,0.178439,0.016077,0.212985,0.031153,0.089260,0.260038,0.280846,0.189144,1,0.302132,0.136060,-0.033565,0.127304,0.162620,0.002148,-0.010831],
[0.222332,0.084575,0.178329,0.071669,0.219433,0.143477,0.158510,0.215729,0.196935,0.045861,0.302132,1,0.124489,0.045985,0.184321,0.089931,0.188370,0.039210],
[0.239717,0.194446,0.160421,0.054474,0.192031,0.006406,0.039161,0.262920,0.161861,0.232779,0.136060,0.124489,1,0.069998,0.035640,0.033140,0.077233,0.064030],
[0.025894,-0.060199,0.053183,-0.011125,0.069685,0.438307,0.382785,-0.029749,0.021661,0.015888,-0.033565,0.045985,0.069998,1,-0.016710,0.069209,0.166523,0.156223],
[0.110746,0.157577,0.057680,-0.049618,0.105231,0.392460,0.425796,0.099039,0.037930,0.107157,0.127304,0.184321,0.035640,-0.016710,1,0.036173,0.086182,0.100543],
[0.064425,0.082277,0.143760,0.032321,0.100752,0.525983,0.552621,0.059861,0.044064,0.029134,0.162620,0.089931,0.033140,0.069209,0.036173,1,-0.044376,-0.010088],
[0.099526,0.110194,0.085660,0.092029,0.063555,0.383006,0.374938,0.133993,0.060002,0.136760,0.002148,0.188370,0.077233,0.166523,0.086182,-0.044376,1,0.075396],
[0.086577,0.027824,0.091187,-0.055235,0.043708,0.290338,0.335203,0.051964,0.075110,0.094356,-0.010831,0.039210,0.064030,0.156223,0.100543,-0.010088,0.075396,1]
];

const labels = [
    "RESTORAN","BANK","MENARA","OPERATOR","ATM","JARAK STASIUN","MEAN WR",
    "PERTOKOAN","MINIMARKET","KEDAI","HOTEL","PENGINAPAN","TOKO",
    "ALAM","BUATAN","BUDAYA","REKREASI","TIRTA"
];

const fullLabels = {
    RESTORAN: "Jumlah Restoran",
    BANK: "Jumlah Bank",
    MENARA: "Jumlah Menara Telekomunikasi",
    OPERATOR: "Jumlah Operator Layanan Seluler",
    ATM: "Jumlah ATM/BRILink",
    "JARAK STASIUN": "Mean Jarak DTW ke Stasiun",
    "MEAN WR": "Mean Weighting Rate (WR)",
    PERTOKOAN: "Jumlah Pertokoan",
    MINIMARKET: "Jumlah Minimarket",
    KEDAI: "Jumlah Kedai",
    HOTEL: "Jumlah Hotel",
    PENGINAPAN: "Jumlah Penginapan",
    TOKO: "Jumlah Toko/Warung",
    ALAM: "Wisata Alam",
    BUATAN: "Wisata Buatan",
    BUDAYA: "Wisata Budaya",
    REKREASI: "Wisata Rekreasi",
    TIRTA: "Wisata Tirta"
};

const labelsY = [...labels].reverse();

const ctx = document.getElementById('correlationChart');

new Chart(ctx, {

    type: 'matrix',

    data: {
        datasets: [{

            data: correlationData.flatMap((row, i) =>
                row.map((v, j) => ({
                    x: labels[j],
                    y: labels[i],
                    v: v
                }))
            ),

            borderWidth: 0,
            borderColor: 'transparent',

            backgroundColor: (ctx) => {

                const v = Math.abs(ctx.raw?.v ?? 0);

                const start = [173, 216, 230]; // biru laut muda
                const end   = [180, 0, 40];    // merah tua

                const r = Math.round(start[0] + (end[0] - start[0]) * v);
                const g = Math.round(start[1] + (end[1] - start[1]) * v);
                const b = Math.round(start[2] + (end[2] - start[2]) * v);

                return `rgb(${r},${g},${b})`;
            },

            width: ({ chart }) => {

                const area = chart.chartArea;
                if (!area) return 0;

                return Math.ceil(
                    area.width / labels.length
                );
            },

            height: ({ chart }) => {

                const area = chart.chartArea;
                if (!area) return 0;

                return Math.ceil(
                    area.height / labels.length
                );
            }
        }]
    },

    options: {

        responsive: true,
        maintainAspectRatio: false,

        layout: {
            padding: {
                left: 20,
                bottom: 20,
                top: 20,
                right: 20
            }
        },

        plugins: {

            legend: {
                display: false
            },

        tooltip: {

            displayColors: false,

            callbacks: {

                title: (items) => {

                    const d = items[0].raw;

                    return `${fullLabels[d.x]} ↔ ${fullLabels[d.y]}`;
                },

                label: (ctx) => {

                    const corr = ctx.raw.v;
                    const absCorr = Math.abs(corr);

                    let kategori = '';

                    if (absCorr <= 0.19)
                        kategori = 'Sangat Rendah';
                    else if (absCorr <= 0.39)
                        kategori = 'Rendah';
                    else if (absCorr <= 0.59)
                        kategori = 'Sedang';
                    else if (absCorr <= 0.79)
                        kategori = 'Tinggi';
                    else
                        kategori = 'Sangat Tinggi';

                    const arah =
                        corr >= 0
                            ? 'Positif'
                            : 'Negatif';

                    return [
                        `Korelasi : ${corr.toFixed(3)}`,
                        `Arah : ${arah}`,
                        `Kategori : ${kategori}`
                    ];
                }
            }
        }
        },

        scales: {

            x: {

                type: 'category',
                labels: labels,

                offset: false,

            ticks: {
                autoSkip: false,
                maxRotation: 45,
                minRotation: 45,
                padding: 30,
                font: {
                    size: 12,
                    weight: 'bold'
                }
            },

                grid: {
                    display: false,
                    drawBorder: false
                }
            },

            y: {

                type: 'category',
                labels: labelsY,

                offset: false,

                ticks: {
                    autoSkip: false,
                    padding: 30,
                    font: {
                        size: 12,
                        weight: 'bold'
                    }
                },

                grid: {
                    display: false,
                    drawBorder: false
                }
            }
        }
    }
});

// ===============================
// HISTOGRAM LOOP (UNCHANGED)
// ===============================
const container = document.getElementById('container');
const selector = document.getElementById('variableSelector');

Object.keys(dataset).forEach((key) => {

    const option = document.createElement('option');

    option.value = key;
    option.textContent = dataset[key].label;

    selector.appendChild(option);

});

selector.addEventListener('change', () => {

    container.innerHTML = '';

    if (!selector.value) return;

    const key = selector.value;

    const data = dataset[key].data;
    const label = dataset[key].label;

    const stats = descriptiveStats(data);
    const hist = createHistogram(data);

    const chartId = "selectedChart";

    container.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6
                    bg-white rounded-3xl shadow-xl p-6">

            <div>

                <h2 class="text-xl font-bold text-[#1A237E] mb-4">
                    ${label}
                </h2>

                <canvas id="${chartId}"></canvas>

            </div>

            <div class="bg-blue-50 rounded-2xl p-6">

                <h3 class="font-bold text-[#1A237E] mb-4">
                    Statistik Deskriptif
                </h3>

                <div class="space-y-2">

                    <div class="flex justify-between">
                        <span>Mean</span>
                        <span>${stats.mean.toFixed(2)}</span>
                    </div>

                    <div class="flex justify-between">
                        <span>Modus</span>
                        <span>
                            ${stats.mode.length > 1
                                ? stats.mode.join(', ')
                                : stats.mode[0]}
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span>Q1</span>
                        <span>${stats.q1.toFixed(2)}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span>Median (Q2)</span>
                        <span>${stats.median.toFixed(2)}</span>
                    </div>

                    <div class="flex justify-between">
                        <span>Q3</span>
                        <span>${stats.q3.toFixed(2)}</span>
                    </div>

                    <div class="flex justify-between">
                        <span>Minimum</span>
                        <span>${stats.min.toFixed(2)}</span>
                    </div>

                    <div class="flex justify-between">
                        <span>Maximum</span>
                        <span>${stats.max.toFixed(2)}</span>
                    </div>

                    <div class="flex justify-between">
                        <span>Standar Deviasi</span>
                        <span>${stats.std.toFixed(2)}</span>
                    </div>

                    <div class="flex justify-between text-[#1A237E] font-bold pb-2 border-t border-[#1A237E]">
                        <span>Jumlah Data</span>
                        <span class="font-bold text-[#1A237E]">${data.length}</span>
                    </div>

                </div>

            </div>

        </div>
    `;

    new Chart(document.getElementById(chartId), {

        data: {

            labels: hist.labels,

            datasets: [
                {
                    type: 'bar',
                    data: hist.values,
                    backgroundColor: 'rgba(37,99,235,0.6)'
                },
                {
                    type: 'line',
                    data: smooth(hist.values),
                    borderColor: '#1E3A8A',
                    tension: 0.4,
                    pointRadius: 0
                }
            ]
        },

        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            }
        }

    });

});

</script>
@endsection