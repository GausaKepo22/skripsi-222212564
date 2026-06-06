@extends('layouts.app')

@section('title', 'Dashboard Potensi Pengembangan Pariwisata')

@section('content')
<!-- Hero Section -->
<section id="home" class="h-[600px] md:h-[800px] flex flex-col justify-center text-white bg-cover bg-center relative"
style="background-image: url('https://asset-2.tstatic.net/newsmaker/foto/bank/images/Obyek-Wisata-Klaten-3.jpg');">
  <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-transparent via-60% to-white"></div>
  <div class="relative text-center px-6 flex-1 flex flex-col justify-center">
      <h2
        class="text-4xl md:text-4xl font-bold mb-4 leading-tight text-white"
        style="
            text-shadow:
            0 0 8px rgba(7,36,64,0.8),
            0 0 16px rgba(7,36,64,0.6),
            0 4px 10px rgba(0,0,0,0.4);
        ">
        ANALISIS SPASIAL POTENSI PENGEMBANGAN PARIWISATA
    </h2>
    <p
        class="text-lg font-bold mb-6 text-white"
        style="
            text-shadow:
            0 2px 4px rgba(0,0,0,0.5),
            0 6px 12px rgba(7,36,64,0.5);
        ">
      Menggunakan Integrasi Data 
      <span class="italic">Point of Interest</span> (POI) dan 
      <span class="italic">Official Statistics</span> 
      <br> (Studi Kasus: Kabupaten Klaten)
    </p>
  </div>
</section>

<!-- Peta Indeks Potensi -->
<section id="peta-indeks" class="py-10 md:py-16 bg-white">
  <div class="max-w-7xl mx-auto px-6">

    <h3 class="text-3xl font-bold mb-8 text-[#1A237E] text-center">
      PETA PERSEBARAN INDEKS POTENSI PENGEMBANGAN PARIWISATA KABUPATEN KLATEN, JAWA TENGAH TAHUN 2026
    </h3>

<div class="bg-white rounded-2xl shadow-lg p-4">
  
  <div class="flex flex-col lg:flex-row gap-4">

    <div class="flex-1 relative">

      <button id="btn-refresh-peta"
        class="absolute top-4 right-4 z-10
              bg-white hover:bg-gray-100
              text-gray-700 font-semibold
              px-4 py-2 rounded-lg
              shadow-lg border border-gray-300
              transition duration-300">

        🔄 Refresh Peta
      </button>

      <div id="map-indeks" class="w-full h-[800px] rounded-xl"></div>

    </div>

    <!-- PANEL PENCARIAN -->
    <div class="w-full lg:w-[320px] bg-blue-50 rounded-2xl p-5 shadow-md h-fit">

      <div class="border-t border-gray-200">
        <h4 class="text-xl font-bold text-[#1A237E] mb-5 text-center">
          PENCARIAN WILAYAH
        </h4>
      </div>
      <!-- Dropdown Kecamatan -->
      <div class="mb-4">
        <label class="block text-sm font-semibold mb-2">
          Nama Kecamatan
        </label>

        <select id="filter-kecamatan"
          class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">

          <option value="">-- Semua Kecamatan --</option>
        </select>
      </div>

      <!-- Dropdown Desa -->
      <div class="mb-5">
        <label class="block text-sm font-semibold mb-2">
          Nama Desa
        </label>

        <select id="filter-desa"
          class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">

          <option value="">-- Pilih Desa --</option>
        </select>
      </div>

    <button id="btn-cari-desa"
      class="w-full bg-blue-700 hover:bg-blue-900 text-white font-semibold py-3 rounded-xl transition duration-300">

      Cari Lokasi
    </button>

    <!-- LEGENDA -->
    <div class="bg-white rounded-xl p-4 shadow-sm mb-5 mt-8 border border-gray-200">

      <h5 class="font-bold text-[#1A237E] mb-3">
        Legenda Kategori
      </h5>

      <div class="space-y-3 text-sm">

        <div class="flex items-center gap-3">
          <span class="w-5 h-5 bg-green-500 rounded"></span>
          <span>Tinggi</span>
        </div>

        <div class="flex items-center gap-3">
          <span class="w-5 h-5 bg-yellow-400 rounded"></span>
          <span>Sedang</span>
        </div>

        <div class="flex items-center gap-3">
          <span class="w-5 h-5 bg-red-500 rounded"></span>
          <span>Rendah</span>
        </div>

      </div>

    </div>
      <!-- INFORMASI METODE -->
      <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-200">

        <h5 class="font-bold text-[#1A237E] mb-3">
          Informasi Metode
        </h5>

        <p class="text-sm text-gray-700 leading-relaxed text-justify">
          Indeks Potensi Pengembangan Pariwisata dihitung menggunakan
          <span class="font-semibold text-[#1A237E]">
            metode Entropy Weighting
          </span>
          untuk menentukan bobot setiap indikator secara objektif berdasarkan
          variasi data. Selanjutnya, hasil indeks diklasifikasikan menggunakan
          <span class="font-semibold text-[#1A237E]">
            metode Natural Breaks (Jenks)
          </span>
          sehingga terbentuk kategori potensi
          <span class="font-semibold text-green-600">Tinggi</span>,
          <span class="font-semibold text-yellow-500">Sedang</span>, dan
          <span class="font-semibold text-red-600">Rendah</span>.
        </p>

      </div>

    </div>
  </div>

</div>

  </div>
</section>

<!-- TABEL INDEKS -->
<section id="tabel-indeks" class="py-10 bg-slate-50">

  <div class="max-w-7xl mx-auto px-6">

    <h3 class="text-3xl font-bold mb-8 text-[#1A237E] text-center">
      TABEL INDEKS POTENSI PENGEMBANGAN PARIWISATA
    </h3>

    <!-- SEARCH -->
    <div class="mb-5">
      <input
        type="text"
        id="search-table"
        placeholder="Cari desa atau kecamatan..."
        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
      >
    </div>

    <!-- TABLE -->
    <div class="overflow-x-auto bg-white rounded-2xl shadow-lg">

      <table class="min-w-full text-sm text-left">

        <thead class="bg-[#1A237E] text-white">

          <tr>

            <th class="px-4 py-3">Rank Indeks</th>

            <th class="px-4 py-3">Kecamatan</th>

            <th class="px-4 py-3">Desa</th>

            <th class="px-4 py-3">Indeks</th>

            <th class="px-4 py-3">Kategori Indeks</th>

            <th class="px-4 py-3">Skor IDM</th>

            <th class="px-4 py-3">Skor Assesment</th>

          </tr>

        </thead>

        <tbody id="table-body" class="divide-y divide-gray-200">

        </tbody>

      </table>

        <!-- PAGINATION -->
        <div class="flex justify-between items-center px-4 py-4 border-t">
          <div id="pagination-info" class="text-sm text-gray-600">
            Menampilkan 1-20 data
          </div>

          <div class="flex gap-2">

            <button id="prev-page"
              class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded-lg text-sm font-semibold">

              Prev
            </button>

            <button id="next-page"
              class="px-4 py-2 bg-blue-700 hover:bg-blue-900 text-white rounded-lg text-sm font-semibold">

              Next
            </button>

          </div>

        </div>

      <!-- EXPORT BUTTON -->
      <div class="flex gap-3 px-4 py-4 border-t">
        
        <button id="export-csv"
          class="px-4 py-2 bg-emerald-700 hover:bg-emerald-900 text-white rounded-lg text-sm font-semibold">
          Export CSV
        </button>

        <button id="export-excel"
          class="px-4 py-2 bg-emerald-700 hover:bg-emerald-900 text-white rounded-lg text-sm font-semibold">
          Export Excel
        </button>

    </div>
    <!-- CATATAN INDEKS -->
    <div class="px-4 pb-5">

      <div class="mt-3 bg-blue-50 border-l-4 border-[#1A237E] rounded-r-xl p-4">

        <h5 class="font-semibold text-[#1A237E] mb-2">
          Catatan Interpretasi Indeks
        </h5>

        <p class="text-sm text-gray-700 leading-relaxed text-justify mt-2">
          Nilai Indeks Potensi Pengembangan Pariwisata pada penelitian ini
          ditampilkan dalam rentang <strong>0–100</strong> untuk memudahkan
          interpretasi hasil. Nilai indeks diperoleh melalui metode <strong>Entropy Weighting Method
          (EWM)</strong>, yaitu hasil agregasi tertimbang dari indikator-indikator
          yang telah dinormalisasi ke dalam rentang 0–1. Karena indeks yang
          dihasilkan juga berada pada rentang 0–1, maka dilakukan transformasi
          sederhana dengan mengalikan nilai indeks sebesar 100 sehingga diperoleh
          skala 0–100. Transformasi ini tidak mengubah peringkat maupun hubungan
          relatif antarwilayah, melainkan hanya mengubah skala penyajian agar
          lebih mudah dipahami dan dibandingkan.
        </p>

      </div>

    </div>

  </div>

</section>

<!-- ===================================================== -->
<!-- SCATTER PLOT KORELASI -->
<!-- ===================================================== -->

<section id="scatter-korelasi" class="py-12 bg-white">

    <div class="max-w-7xl mx-auto px-6">

        <h3 class="text-3xl font-bold text-center text-[#1A237E] mb-8">
            KORELASI PEARSON INDEKS POTENSI PENGEMBANGAN PARIWISATA <br> DENGAN IDM DAN ASSESSMENT
        </h3>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <!-- CARD IDM -->
            <div class="bg-blue-50 rounded-3xl shadow-xl border p-6">

                <h4 class="text-xl font-bold text-[#1A237E] mb-4 text-center">
                    Indeks Potensi Pengembangan Pariwisata vs IDM
                </h4>

                <div style="height:500px;">
                    <canvas id="scatterIDM"></canvas>
                </div>
                <div class="mt-4 bg-white border-2 border-[#1A237E] text-[#1A237E] rounded-xl p-4 text-sm leading-relaxed">
                    <strong>Catatan:</strong> 
                    Titik pada scatter plot terdiri atas <strong>391 desa</strong> dari
                    <strong>401 desa</strong> yang ada di Kabupaten Klaten.
                    Sebanyak <strong>10 desa</strong> tidak diikutsertakan karena nilai
                    <strong>IDM</strong> tidak tersedia.
                </div>
            </div>

            <!-- CARD ASSESMENT -->
            <div class="bg-blue-50 rounded-3xl shadow-xl border p-6">

                <h4 class="text-xl font-bold text-[#1A237E] mb-4 text-center">
                    Indeks Potensi Pengembangan Pariwisata vs Assessment
                </h4>

                <div style="height:500px;">
                    <canvas id="scatterAssessment"></canvas>
                </div>

                <div class="mt-4 bg-white border-2 border-[#1A237E] text-[#1A237E] rounded-xl p-4 text-sm leading-relaxed">
                    <strong>Catatan:</strong>
                    Titik pada scatter plot terdiri atas <strong>39 desa</strong> dari
                    <strong>401 desa</strong> yang ada di Kabupaten Klaten.
                    Hal ini karena hanya <strong>39 desa</strong> yang dilakukan
                    <strong>assessment</strong>, yaitu desa-desa yang telah tercatat sebagai
                    <strong>Desa Wisata</strong> dan memiliki <strong>SK Desa Wisata</strong>.
                </div>

            </div>

        </div>

    </div>

</section>

</div>

@endsection

@push('scripts')
<script src="https://cdn.sheetjs.com/xlsx-latest/package/dist/xlsx.full.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@3.0.1"></script>
<script> Chart.register(ChartAnnotation); </script>

<script>
// Fungsi capitalize
function capitalizeFirstLetter(str) {
  if (!str) return '';
  return str.toLowerCase().replace(/\b\w/g, char => char.toUpperCase());
}

document.addEventListener('DOMContentLoaded', function () {

  // ============================
  // MAP INDEKS POTENSI
  // ============================

  const mapIndeks = L.map('map-indeks').setView([-7.566789, 110.615915], 11);

  let indeksLayer;

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
  }).addTo(mapIndeks);

  // ============================
  // WARNA KATEGORI
  // ============================

  function getColor(kategori) {

    switch ((kategori || '').toLowerCase()) {

      case 'tinggi':
        return '#16a34a';

      case 'sedang':
        return '#facc15';

      case 'rendah':
        return '#dc2626';

      default:
        return '#9ca3af';
    }
  }

  // ============================
  // LOAD GEOJSON
  // ============================

  fetch('{{ asset("data/Indeks_Entropy.geojson") }}')

    .then(res => res.json())

    .then(data => {

      // ============================
      // LAYER GEOJSON
      // ============================

      indeksLayer = L.geoJSON(data, {

        style: function(feature) {

          return {
            color: '#ffffff',
            weight: 1,
            fillColor: getColor(feature.properties.Kategori),
            fillOpacity: 0.8
          };
        },

        onEachFeature: function(feature, layer) {

          const props = feature.properties;

          layer.bindPopup(`
            <div style="min-width:220px">

              <h3 style="font-weight:bold; font-size:16px; margin-bottom:8px;">
                ${props.Desa || '-'}
              </h3>

              <table style="width:100%; font-size:13px;">
                <tr>
                  <td><b>Kecamatan</b></td>
                  <td>: ${props.Kecamatan || '-'}</td>
                </tr>

                <tr>
                  <td><b>Indeks</b></td>
                  <td>: ${props.Indeks ?? '-'}</td>
                </tr>

                <tr>
                  <td><b>IDM</b></td>
                  <td>: ${props.IDM || '-'}</td>
                </tr>

                <tr>
                  <td><b>Assessment</b></td>
                  <td>: ${props.Assesment || '-'}</td>
                </tr>

                <tr>
                  <td><b>Rank Indeks</b></td>
                  <td>: ${props.Rank_Indeks || '-'}</td>
                </tr>

                <tr>
                  <td><b>Rank IDM</b></td>
                  <td>: ${props.Rank_IDM || '-'}</td>
                </tr>

                <tr>
                  <td><b>Rank Assessment</b></td>
                  <td>: ${props.Rank_Assesment || '-'}</td>
                </tr>

                <tr>
                  <td><b>Kategori</b></td>
                  <td>: <b>${props.Kategori || '-'}</b></td>
                </tr>

              </table>
            </div>
          `);

          // Hover effect
          layer.on({

            mouseover: function(e) {

              e.target.setStyle({
                weight: 3,
                color: '#000',
                fillOpacity: 1
              });
            },

            mouseout: function(e) {

              indeksLayer.resetStyle(e.target);
            }
          });
        }

      }).addTo(mapIndeks);

      // ============================
      // FIT BOUNDS
      // ============================

      const bounds = indeksLayer.getBounds();

      if (bounds.isValid()) {

        mapIndeks.fitBounds(bounds, {
          padding: [20, 20],
          maxZoom: 14
        });
      }

      mapIndeks.invalidateSize();

      // ======================================
      // DROPDOWN KECAMATAN & DESA
      // ======================================

      const kecamatanSelect =
        document.getElementById('filter-kecamatan');

      const desaSelect =
        document.getElementById('filter-desa');

      const btnCari =
        document.getElementById('btn-cari-desa');

      const btnRefresh =
      document.getElementById('btn-refresh-peta');

      // Ambil kecamatan unik
      const daftarKecamatan = [

        ...new Set(
          data.features.map(
            f => f.properties.Kecamatan
          )
        )

      ].sort();

      // Isi dropdown kecamatan
      daftarKecamatan.forEach(kec => {

        const option =
          document.createElement('option');

        option.value = kec;
        option.textContent = kec;

        kecamatanSelect.appendChild(option);
      });

      // ======================================
      // FILTER DESA
      // ======================================

      kecamatanSelect.addEventListener(
        'change',
        function () {

          const kecamatanTerpilih =
            this.value;

          desaSelect.innerHTML =
            '<option value="">-- Pilih Desa --</option>';

          const daftarDesa = data.features

            .filter(f =>

              !kecamatanTerpilih ||

              f.properties.Kecamatan ===
              kecamatanTerpilih
            )

            .map(f => f.properties.Desa)

            .sort();

          const desaUnik =
            [...new Set(daftarDesa)];

          desaUnik.forEach(desa => {

            const option =
              document.createElement('option');

            option.value = desa;
            option.textContent = desa;

            desaSelect.appendChild(option);
          });
        }
      );

      // ======================================
      // TOMBOL CARI
      // ======================================

      btnCari.addEventListener(
        'click',
        function () {

          const desaDipilih =
            desaSelect.value;

          if (!desaDipilih) {

            alert(
              'Silakan pilih desa terlebih dahulu.'
            );

            return;
          }

          indeksLayer.eachLayer(function(layer) {

            const props =
              layer.feature.properties;

            if (props.Desa === desaDipilih) {

              mapIndeks.fitBounds(
                layer.getBounds(),
                {
                  padding: [40, 40],
                  maxZoom: 15
                }
              );

              layer.openPopup();
            }
          });
        }
      );

// ======================================
// TABEL DATA
// ======================================

const tableBody =
  document.getElementById('table-body');

const prevPageBtn =
  document.getElementById('prev-page');

const nextPageBtn =
  document.getElementById('next-page');

const paginationInfo =
  document.getElementById('pagination-info');

let currentPage = 1;

const rowsPerPage = 10;

let filteredData = [];

const searchTable =
  document.getElementById('search-table');

// Sort berdasarkan rank indeks
const sortedFeatures = data.features.sort((a, b) => {

  return (
    Number(a.properties.Rank_Indeks || 9999)
    -
    Number(b.properties.Rank_Indeks || 9999)
  );

});

// ===============================
// EXPORT BUTTON EVENT
// ===============================

const exportCSVBtn = document.getElementById('export-csv');
const exportExcelBtn = document.getElementById('export-excel');

exportCSVBtn.addEventListener('click', function () {
  exportCSV(filteredData.length ? filteredData : sortedFeatures);
});

exportExcelBtn.addEventListener('click', function () {
  exportExcel(filteredData.length ? filteredData : sortedFeatures);
});



// Render tabel
function renderTable(features) {

  filteredData = features;

  tableBody.innerHTML = '';

  const start =
    (currentPage - 1) * rowsPerPage;

  const end =
    start + rowsPerPage;

  const paginatedItems =
    filteredData.slice(start, end);

  paginatedItems.forEach(feature => {

    const props = feature.properties;

    let kategoriColor = '';

    switch ((props.Kategori || '').toLowerCase()) {

      case 'tinggi':
        kategoriColor =
          'bg-green-100 text-green-700';
        break;

      case 'sedang':
        kategoriColor =
          'bg-yellow-100 text-yellow-700';
        break;

      case 'rendah':
        kategoriColor =
          'bg-red-100 text-red-700';
        break;

      default:
        kategoriColor =
          'bg-gray-100 text-gray-700';
    }

    tableBody.innerHTML += `

      <tr class="hover:bg-blue-50 transition">

        <td class="px-4 py-3 font-semibold">
          ${props.Rank_Indeks || '-'}
        </td>

        <td class="px-4 py-3">
          ${props.Kecamatan || '-'}
        </td>

        <td class="px-4 py-3">
          ${props.Desa || '-'}
        </td>

        <td class="px-4 py-3">
          ${props.Indeks ?? '-'}
        </td>

        <td class="px-4 py-3">

          <span class="
            px-3 py-1 rounded-full text-xs font-semibold
            ${kategoriColor}
          ">
            ${props.Kategori || '-'}
          </span>

        </td>

        <td class="px-4 py-3">
          ${props.IDM || '-'}
        </td>

        <td class="px-4 py-3">
          ${props.Assesment || '-'}
        </td>

      </tr>
    `;
  });

  // INFO PAGINATION
  paginationInfo.innerHTML = `
    Menampilkan
    ${start + 1}
    -
    ${Math.min(end, filteredData.length)}
    dari
    ${filteredData.length} data
  `;

  // DISABLE BUTTON
  prevPageBtn.disabled =
    currentPage === 1;

  nextPageBtn.disabled =
    end >= filteredData.length;

  prevPageBtn.classList.toggle(
    'opacity-50',
    currentPage === 1
  );

  nextPageBtn.classList.toggle(
    'opacity-50',
    end >= filteredData.length
  );
}

// Render awal tabel
renderTable(sortedFeatures);

// ======================================
// FUNGSI KORELASI PEARSON
// ======================================

function pearsonCorrelation(data) {

    const n = data.length;

    const xs = data.map(d => d.x);
    const ys = data.map(d => d.y);

    const meanX =
        xs.reduce((a,b)=>a+b,0)/n;

    const meanY =
        ys.reduce((a,b)=>a+b,0)/n;

    let numerator = 0;
    let denomX = 0;
    let denomY = 0;

    for(let i=0;i<n;i++){

        const dx = xs[i] - meanX;
        const dy = ys[i] - meanY;

        numerator += dx * dy;
        denomX += dx * dx;
        denomY += dy * dy;
    }

    return numerator /
        Math.sqrt(denomX * denomY);
}

// ======================================
// REGRESI LINEAR
// ======================================

function linearRegression(data){

    const n = data.length;

    const xs = data.map(d => d.x);
    const ys = data.map(d => d.y);

    const meanX =
        xs.reduce((a,b)=>a+b,0)/n;

    const meanY =
        ys.reduce((a,b)=>a+b,0)/n;

    let numerator = 0;
    let denominator = 0;

    for(let i=0;i<n;i++){

        numerator +=
            (xs[i]-meanX) *
            (ys[i]-meanY);

        denominator +=
            (xs[i]-meanX) *
            (xs[i]-meanX);
    }

    const slope =
        numerator / denominator;

    const intercept =
        meanY - slope * meanX;

    return {
        slope,
        intercept
    };
}

// ======================================
// SCATTER INDEKS VS IDM
// ======================================

const scatterIDMData = data.features

  .filter(f =>
      f.properties.IDM !== null &&
      f.properties.IDM !== '' &&
      !isNaN(Number(f.properties.IDM))
  )

  .map(f => ({
      x: Number(f.properties.Indeks),
      y: Number(f.properties.IDM),
      desa: f.properties.Desa
  }));

const regIDM =
    linearRegression(scatterIDMData);

const corrIDM =
    pearsonCorrelation(scatterIDMData);

const xMinIDM =
    Math.min(...scatterIDMData.map(d=>d.x));

const xMaxIDM =
    Math.max(...scatterIDMData.map(d=>d.x));

const xMidIDM = (xMinIDM + xMaxIDM) / 2;

const yMidIDM =
    regIDM.slope * xMidIDM +
    regIDM.intercept +
    0.03;

const regressionLineIDM = [

    {
        x: xMinIDM,
        y:
            regIDM.slope*xMinIDM +
            regIDM.intercept
    },

    {
        x: xMaxIDM,
        y:
            regIDM.slope*xMaxIDM +
            regIDM.intercept
    }

];

new Chart(
    document.getElementById('scatterIDM'),
    {
        type: 'scatter',

        data: {
                datasets: [
                {
                    label: 'Garis Regresi',
                    type: 'line',
                    data: regressionLineIDM,
                    backgroundColor: '#dc2626',
                    borderColor: '#dc2626',
                    borderWidth: 3,
                    pointRadius: 0,
                    tension: 0
                },
                {
                    label: 'Desa',
                    data: scatterIDMData,
                    backgroundColor: 'rgb(26, 35, 126)',
                    borderColor: 'rgb(26, 35, 126)',
                    pointRadius: 5,
                    pointHoverRadius: 8
                }

                ]
        },

        options: {

            responsive: true,
            maintainAspectRatio: false,

            plugins: {

                tooltip: {

                    callbacks: {

                    label: function(context) {

                        if(context.dataset.label === 'Garis Regresi'){
                            return '';
                        }

                        return [
                            'Desa : ' + context.raw.desa,
                            'Indeks : ' + context.raw.x,
                            'IDM : ' + context.raw.y
                        ];
                    }
                    }
                },
                  annotation: {

                      annotations: {

                          corrText: {

                              type: 'label',

                              xValue: xMidIDM,
                              yValue: yMidIDM,

                              content: [
                                    `r = ${corrIDM.toFixed(3)}`,
                                    `R² = ${(corrIDM*corrIDM).toFixed(3)}`
                                  ],

                              backgroundColor:
                                  'rgba(255,255,255,0.8)',

                              borderColor:
                                  '#000',

                              borderWidth: 1,

                              padding: 6
                          }
                      }
                  }
            },

            scales: {

                x: {

                    title: {
                        display: true,
                        text: 'Indeks Potensi Pengembangan Pariwisata'
                    }
                },

                y: {

                    title: {
                        display: true,
                        text: 'IDM'
                    }
                }
            }
        }
    }
);

// ======================================
// SCATTER INDEKS VS ASSESSMENT
// ======================================

const scatterAssessmentData = data.features

  .filter(f =>
      f.properties.Assesment !== null &&
      f.properties.Assesment !== '' &&
      !isNaN(Number(f.properties.Assesment))
  )

  .map(f => ({
      x: Number(f.properties.Indeks),
      y: Number(f.properties.Assesment),
      desa: f.properties.Desa
  }));

const regAssessment =
    linearRegression(
        scatterAssessmentData
    );

const corrAssessment =
    pearsonCorrelation(
        scatterAssessmentData
    );

const xMinAssessment =
    Math.min(
        ...scatterAssessmentData.map(
            d => d.x
        )
    );

const xMaxAssessment =
    Math.max(
        ...scatterAssessmentData.map(
            d => d.x
        )
    );

const xMidAssessment =
    (xMinAssessment + xMaxAssessment) / 2;

const yMidAssessment =
    regAssessment.slope * xMidAssessment +
    regAssessment.intercept +
    0.03;

const regressionLineAssessment = [

{
    x: xMinAssessment,
    y:
        regAssessment.slope *
        xMinAssessment +
        regAssessment.intercept
},

{
    x: xMaxAssessment,
    y:
        regAssessment.slope *
        xMaxAssessment +
        regAssessment.intercept
}

];

new Chart(
    document.getElementById('scatterAssessment'),
    {
        type: 'scatter',

        data: {
          datasets: [
          {
              label: 'Garis Regresi',
              type: 'line',
              data: regressionLineAssessment,
              backgroundColor: '#dc2626',
              borderColor: '#dc2626', 
              borderWidth: 3,
              pointRadius: 0,
              tension: 0
          },

          {
              label: 'Desa',
              data: scatterAssessmentData,
              backgroundColor: 'rgb(26, 35, 126)',
              borderColor: 'rgb(26, 35, 126)',
              pointRadius: 5,
              pointHoverRadius: 8
          }

          ]
        },

        options: {

            responsive: true,
            maintainAspectRatio: false,

            plugins: {

                tooltip: {

                    callbacks: {

                        label: function(context) {

                            return [
                                'Desa : ' + context.raw.desa,
                                'Indeks : ' + context.raw.x,
                                'Assessment : ' + context.raw.y
                            ];
                        }
                    }
                },
                annotation: {

                    annotations: {

                        corrText: {

                            type: 'label',

                            xValue: xMidAssessment,
                            yValue: yMidAssessment,

                            content: [
                              `r = ${corrAssessment.toFixed(3)}`,
                              `R² = ${(corrAssessment*corrAssessment).toFixed(3)}`
                            ],

                            backgroundColor:
                                'rgba(255,255,255,0.8)',

                            borderColor:
                                '#000',

                            borderWidth: 1,

                            padding: 6
                        }
                    }
                }
            },

            scales: {

                x: {

                    title: {
                        display: true,
                        text: 'Indeks Potensi Pengembangan Pariwisata'
                    }
                },

                y: {

                    title: {
                        display: true,
                        text: 'Assessment'
                    }
                }
            }
        }
    }
);

// ======================================
// PAGINATION BUTTON
// ======================================

nextPageBtn.addEventListener(
  'click',
  function () {

    if (
      currentPage * rowsPerPage
      <
      filteredData.length
    ) {

      currentPage++;

      renderTable(filteredData);
    }
  }
);

prevPageBtn.addEventListener(
  'click',
  function () {

    if (currentPage > 1) {

      currentPage--;

      renderTable(filteredData);
    }
  }
);

// ===============================
// EXPORT CSV
// ===============================
function exportCSV(data) {

  let csv = [
    ["Rank Indeks", "Kecamatan", "Desa", "Indeks", "Kategori", "IDM", "Assessment"]
  ];

  data.forEach(f => {
    const p = f.properties;

    csv.push([
      p.Rank_Indeks || '',
      p.Kecamatan || '',
      p.Desa || '',
      p.Indeks ?? '',
      p.Kategori || '',
      p.IDM || '',
      p.Assesment || ''
    ]);
  });

  let csvContent = "data:text/csv;charset=utf-8,"
    + csv.map(e => e.join(",")).join("\n");

  const encodedUri = encodeURI(csvContent);
  const link = document.createElement("a");

  link.setAttribute("href", encodedUri);
  link.setAttribute("download", "data_indeks_potensi.csv");

  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
}

// ===============================
// EXPORT EXCEL (.xlsx)
// ===============================
function exportExcel(data) {

  let exportData = data.map(f => ({
    "Rank Indeks": f.properties.Rank_Indeks,
    "Kecamatan": f.properties.Kecamatan,
    "Desa": f.properties.Desa,
    "Indeks": f.properties.Indeks,
    "Kategori": f.properties.Kategori,
    "IDM": f.properties.IDM,
    "Assessment": f.properties.Assesment
  }));

  const ws = XLSX.utils.json_to_sheet(exportData);
  const wb = XLSX.utils.book_new();

  XLSX.utils.book_append_sheet(wb, ws, "Data Indeks");

  XLSX.writeFile(wb, "data_indeks_potensi.xlsx");
}


      // ======================================
      // SEARCH TABLE
      // ======================================

      searchTable.addEventListener(
        'keyup',
        function () {

          const keyword =
            this.value.toLowerCase();

          const filtered =
            sortedFeatures.filter(feature => {

              const props =
                feature.properties;

              return (

                (props.Desa || '')
                  .toLowerCase()
                  .includes(keyword)

                ||

                (props.Kecamatan || '')
                  .toLowerCase()
                  .includes(keyword)

              );
            });

          currentPage = 1;

          renderTable(filtered);
        }
      );

      // ======================================
      // BUTTON REFRESH PETA
      // ======================================

      btnRefresh.addEventListener(
        'click',
        function () {

          // Reset dropdown
          kecamatanSelect.value = '';
          desaSelect.innerHTML =
            '<option value="">-- Pilih Desa --</option>';

          // Trigger ulang dropdown desa
          kecamatanSelect.dispatchEvent(
            new Event('change')
          );
          
          // Tutup popup
          mapIndeks.closePopup();

          // Zoom kembali ke seluruh layer
          const bounds = indeksLayer.getBounds();

          if (bounds.isValid()) {

            mapIndeks.fitBounds(bounds, {
              padding: [20, 20],
              maxZoom: 14
            });
          }
        }
      );

      // Trigger awal
      kecamatanSelect.dispatchEvent(
        new Event('change')
      );

    })

    .catch(err => {

      console.error(
        'GeoJSON Indeks Error:',
        err
      );

    });

});

</script>

  @endpush