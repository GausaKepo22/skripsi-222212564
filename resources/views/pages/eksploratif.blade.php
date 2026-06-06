@extends('layouts.app')

@section('title', 'Eksploratif KDE Wisata')

@section('content')

<!-- ===================================================== -->
<!-- HERO -->
<!-- ===================================================== -->

<section class="pt-28 pb-16 bg-gradient-to-r from-[#0F172A] via-[#1E3A8A] to-[#2563EB] text-white">

    <div class="max-w-7xl mx-auto px-6 text-center">

        <h1 class="text-4xl md:text-5xl font-extrabold mb-4">
            Analisis Eksploratif
        </h1>

        <p class="max-w-3xl mx-auto text-blue-100 text-lg leading-relaxed">
            Analisis eksploratif persebaran daya tarik wisata di Kabupaten Klaten, Jawa Tengah,
            dilakukan menggunakan metode Kernel Density Estimation (KDE) dan Average Nearest Neighbor (ANN).
        </p>

    </div>

</section>



<!-- ===================================================== -->
<!-- MAP + SIDEBAR FILTER -->
<!-- ===================================================== -->

<section class="py-12 bg-slate-100">

    <div class="max-w-7xl mx-auto px-6">

        <div class="grid lg:grid-cols-4 gap-6">

            <!-- ================================================= -->
            <!-- MAP -->
            <!-- ================================================= -->

            <div class="lg:col-span-3">

                <div class="bg-white rounded-3xl shadow-xl overflow-hidden">

                    <div class="p-6 border-b">

                        <h2 class="text-2xl font-bold text-[#1A237E]">
                            Peta Kernel Density Estimation (KDE) Daya Tarik Wisata
                        </h2>

                        <p class="text-gray-600 mt-2">
                            Visualisasi hotspot kepadatan Daya Tarik Wisata
                            Kabupaten Klaten, Jawa Tengah.
                            <br><br>
                            <span class="font-semibold text-[#1A237E]">
                                Sumber Data:
                            </span>
                            Point of Interest (POI) Google Maps.
                        </p>

                    </div>

                    <div class="relative overflow-hidden">

                        <button id="refreshMap"
                            class="absolute top-4 right-4 z-10
                                bg-white hover:bg-gray-100
                                text-gray-700 font-semibold
                                px-4 py-2 rounded-lg
                                shadow-lg border border-gray-300
                                transition duration-300">

                            🔄 Refresh Peta
                        </button>

                        <div id="map"
                            class="w-full h-[450px] bg-white">
                        </div>

                    </div>

                </div>

            </div>


            <!-- ================================================= -->
            <!-- SIDEBAR FILTER -->
            <!-- ================================================= -->

            <div class="lg:col-span-1">

                <div class="bg-white rounded-3xl shadow-xl p-6 sticky top-24">

                    <h3 class="text-2xl font-bold text-[#1A237E] mb-6 text-center">
                        Filter KDE
                    </h3>

                    <!-- FILTER KATEGORI -->
                    <div class="mb-6">

                        <label class="block mb-2 font-semibold text-gray-700">
                            Kategori Wisata
                        </label>

                        <select id="kategoriSelect"
                            class="w-full border rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500">

                            <option value="all">
                                Semua Kategori
                            </option>

                            @foreach($kategoriList as $kategori)

                                <option value="{{ $kategori }}">
                                    {{ $kategori }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <!-- BANDWIDTH -->
                    <div class="mb-6">

                        <label class="block mb-2 font-semibold text-gray-700">
                            Bandwidth KDE
                        </label>

                        <select id="radiusSelect"
                            class="w-full border rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500">

                            <option value="20" selected>
                                Rendah
                            </option>

                            <option value="35">
                                Sedang
                            </option>

                            <option value="50">
                                Tinggi
                            </option>

                        </select>

                    </div>

                    <!-- STATISTIK -->
                    <div class="bg-blue-50 rounded-2xl p-5">

                        <h3 class="font-bold text-[#1A237E] mb-3">
                            Statistik Data
                        </h3>

                        <div class="space-y-2 text-gray-700 text-sm">

                            <p>
                                Jumlah DTW :
                                <span id="jumlahWisata" class="font-semibold">
                                    {{ $jumlahWisata }}
                                </span>
                            </p>

                            <p>
                                Hotspot :
                                <span class="font-semibold text-red-600">
                                    Area Konsentrasi Tinggi
                                </span>
                            </p>

                        </div>

                    </div>


                    <!-- LEGENDA -->
                    <div class="mt-6">

                        <h3 class="font-bold text-[#1A237E] mb-3">
                            Legenda KDE
                        </h3>

                        <div class="space-y-3 text-sm">

                            <div class="flex items-center gap-3">
                                <div class="w-5 h-5 rounded bg-blue-700"></div>
                                <span>Kepadatan Rendah</span>
                            </div>

                            <div class="flex items-center gap-3">
                                <div class="w-5 h-5 rounded bg-yellow-400"></div>
                                <span>Kepadatan Sedang</span>
                            </div>

                            <div class="flex items-center gap-3">
                                <div class="w-5 h-5 rounded bg-red-600"></div>
                                <span>Kepadatan Tinggi</span>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

<!-- ===================================================== -->
<!-- ANN ANALYSIS -->
<!-- ===================================================== -->

<section class="py-16 bg-white">

    <div class="max-w-7xl mx-auto px-6">

        <div class="bg-white rounded-3xl shadow-xl p-6">

            <h2 class="text-2xl font-bold text-[#1A237E] mb-6 text-center">
                Analisis Average Nearest Neighbor (ANN)
            </h2>

            <div class="overflow-x-auto">

                <table class="w-full text-sm text-left border-collapse">

                    <thead class="bg-blue-50 text-[#1A237E]">
                        <tr>
                            <th class="p-3">Kategori</th>
                            <th class="p-3">Jumlah</th>
                            <th class="p-3">Observed</th>
                            <th class="p-3">Expected</th>
                            <th class="p-3">ANN Ratio</th>
                            <th class="p-3">Pola</th>
                            <th class="p-3">P-Value</th>
                            <th class="p-3">Signifikansi</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($annData as $row)

                            <tr class="border-b hover:bg-gray-50">

                                <td class="p-3 font-semibold">
                                    {{ $row['kategori'] }}
                                </td>

                                <td class="p-3">{{ $row['jumlah'] }}</td>
                                <td class="p-3">{{ $row['observed'] }}</td>
                                <td class="p-3">{{ $row['expected'] }}</td>
                                <td class="p-3">{{ $row['ann'] }}</td>
                                <td class="p-3 font-semibold text-blue-700">
                                    {{ $row['pola'] }}
                                </td>

                                <td class="p-3">{{ $row['p'] }}</td>

                                <td class="p-3 font-semibold text-blue-700">
                                    {{ $row['hasil'] }}
                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

            <p class="text-sm text-gray-500 mt-4 text-center">
                ANN digunakan untuk mengukur pola persebaran titik DTW (cluster, random, atau dispersed).
            </p>

        </div>

    </div>

</section>

@endsection


<!-- ===================================================== -->
<!-- STYLE -->
<!-- ===================================================== -->

@push('styles')

<link rel="stylesheet"
    href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

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

<script src="https://unpkg.com/leaflet.heat/dist/leaflet-heat.js"></script>

<script>

    // =====================================================
    // INIT MAP
    // =====================================================

    const map = L.map('map').setView(
        [-7.705, 110.606],
        11
    );
    map.createPane('markers');
    map.getPane('markers').style.zIndex = 650;
    // =====================================================
    // BASEMAP
    // =====================================================

    L.tileLayer(
        'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
        {
            attribution:
                '&copy; OpenStreetMap'
        }
    ).addTo(map);

    // =====================================================
    // GEOJSON BATAS DESA
    // =====================================================

    fetch('/data/desa-klaten.geojson')

        .then(res => res.json())

        .then(data => {

        L.geoJSON(data, {

        style: {
            color: '#1E293B',
            weight: 1,
            fillColor: '#FFFFFF',
            fillOpacity: 0.4
        }

        }).addTo(map);

        });

    // =====================================================
    // DATA WISATA
    // =====================================================

    let wisataData = @json($wisata);

    let heatLayer;

    let markerLayer = L.featureGroup().addTo(map);

    // =====================================================
    // UPDATE MAP
    // =====================================================

    function updateMap() {

        // Hapus heatmap lama
        if (heatLayer) {
            map.removeLayer(heatLayer);
        }

        // Hapus marker lama
        markerLayer.clearLayers();

        // Filter kategori
        const kategori =
            document.getElementById(
                'kategoriSelect'
            ).value;

        // Radius KDE
        const radius =
            parseInt(
                document.getElementById(
                    'radiusSelect'
                ).value
            );

        // Filter data
        let filtered = wisataData.filter(item => {

            if (kategori === 'all') {
                return true;
            }

            return item.KATEGORI === kategori;

        });

        // Statistik
        document.getElementById(
            'jumlahWisata'
        ).innerText = filtered.length;

        // Heatmap point
        let heatPoints = [];

        filtered.forEach(item => {

            // Heat
            heatPoints.push([
                item.LATITUDE,
                item.LONGITUDE,
                1
            ]);

            // Marker
            let marker = L.circleMarker(
                [
                    item.LATITUDE,
                    item.LONGITUDE
                ],
                {
                    pane: 'markers',
                    radius: 7,
                    fillColor: '#0F172A',
                    color: '#ffffff',
                    weight: 2,
                    fillOpacity: 1
                }
            );

marker.bindPopup(`
    <div style="
        min-width:240px;
        font-family:Inter,Segoe UI,sans-serif;
    ">

        <h3 style="
            color:#1E40AF;
            font-size:16px;
            font-weight:700;
            margin-bottom:10px;
            border-bottom:1px solid #E5E7EB;
            padding-bottom:8px;
        ">
             ${item.NAMA}
        </h3>

        <div style="
            display:flex;
            flex-direction:column;
            gap:8px;
            font-size:13px;
            color:#374151;
        ">

            <div style="
                background:#EFF6FF;
                color:#1D4ED8;
                padding:6px 10px;
                border-radius:8px;
                font-weight:600;
                text-align:center;
            ">
                ${item.KATEGORI ?? '-'}
            </div>

            <div>
                <span style="font-weight:600;">DESA</span><br>
                ${item.DESA ?? '-'}
            </div>

            <div>
                <span style="font-weight:600;">KECAMATAN</span><br>
                ${item.KECAMATAN ?? '-'}
            </div>

            <div style="
                display:grid;
                grid-template-columns:1fr;
                gap:6px;
                margin-top:4px;
                padding-top:8px;
                border-top:1px solid #E5E7EB;
            ">
                <div>
                    <span style="font-weight:600;">🌐 Latitude</span><br>
                    ${item.LATITUDE}
                </div>

                <div>
                    <span style="font-weight:600;">🌐 Longitude</span><br>
                    ${item.LONGITUDE}
                </div>
            </div>

        </div>

        ${
            item.TAUTAN
            ?
            `
            <a href="${item.TAUTAN}"
               target="_blank"
               style="
                    display:flex;
                    align-items:center;
                    justify-content:center;
                    gap:8px;
                    margin-top:14px;
                    padding:10px 12px;
                    border-radius:10px;
                    background:linear-gradient(135deg,#2563EB,#1D4ED8);
                    color:white;
                    text-decoration:none;
                    font-size:13px;
                    font-weight:600;
                    box-shadow:0 4px 12px rgba(37,99,235,.25);
                    transition:all .3s ease;
               "
               onmouseover="this.style.transform='translateY(-2px)'"
               onmouseout="this.style.transform='translateY(0)'"
            >
                🗺️ Buka di Google Maps
            </a>
            `
            :
            ''
        }

    </div>
`);

            marker.addTo(markerLayer);
        });

        // =================================================
        // HEATMAP KDE
        // =================================================

        heatLayer = L.heatLayer(

            heatPoints,

            {
                radius: radius,
                blur: 30,
                maxZoom: 15,
                minOpacity: 0.5,

                gradient: {
                    0.2: '#1E3A8A',
                    0.4: '#2563EB',
                    0.6: '#FACC15',
                    0.8: '#F97316',
                    1.0: '#DC2626'
                }

            }

      ).addTo(map);

    }

    // =====================================================
    // EVENT
    // =====================================================

    document.getElementById(
        'kategoriSelect'
    ).addEventListener(
        'change',
        updateMap
    );

    document.getElementById(
        'radiusSelect'
    ).addEventListener(
        'change',
        updateMap
    );

    // Refresh peta
    document.getElementById('refreshMap')
    .addEventListener('click', function () {

        document.getElementById('kategoriSelect').value = 'all';
        document.getElementById('radiusSelect').value = '20';

        map.setView(
            [-7.705, 110.606],
            11
        );

        updateMap();
    });
    // =====================================================
    // LOAD AWAL
    // =====================================================

    updateMap();

</script>

@endpush