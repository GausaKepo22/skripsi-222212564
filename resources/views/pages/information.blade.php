@extends('layouts.app2')

@section('title', 'Tim Penelitian')

@section('content')

<!-- ================= PROFIL TIM ================= -->
<section id="tim-penelitian" class="mt-5 py-24 bg-blue-50">
    <div class="max-w-6xl mx-auto px-6">

        <!-- Judul -->
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-[#1A237E] mb-4">
                PENANGGUNG JAWAB PENELITIAN
            </h2>
        </div>

        <!-- Card Team -->
        <div class="grid md:grid-cols-2 gap-10 max-w-4xl mx-auto">

            <!-- Dewi -->
            <div
                class="group bg-white rounded-3xl shadow-lg hover:shadow-2xl transition duration-300 overflow-hidden border border-slate-100">

                <div class="bg-gradient-to-r from-[#1A237E] to-[#3949AB] h-24"></div>

                <div class="px-8 pb-8 text-center -mt-16">

                    <img src="{{ asset('images/dewi.jpeg') }}"
                         class="w-32 h-40 object-cover rounded-2xl mx-auto border-4 border-white shadow-lg"
                         alt="Foto Dewi Nurihandayani">

                    <h3 class="mt-5 text-2xl font-bold text-[#1A237E]">
                        Dewi Nurihandayani
                    </h3>

                    <span
                        class="inline-block mt-2 px-4 py-1 bg-blue-100 text-[#1A237E] text-sm font-medium rounded-full">
                        Peneliti Utama
                    </span>

                    <p class="mt-5 text-gray-600 leading-relaxed">
                        Bertanggung jawab dalam pengumpulan data, integrasi
                        Point of Interest (POI), analisis spasial,
                        pembangunan dashboard, serta penyusunan hasil penelitian.
                    </p>

                </div>
            </div>

            <!-- Dosen -->
            <div
                class="group bg-white rounded-3xl shadow-lg hover:shadow-2xl transition duration-300 overflow-hidden border border-slate-100">

                <div class="bg-gradient-to-r from-[#1A237E] to-[#3949AB] h-24"></div>

                <div class="px-8 pb-8 text-center -mt-16">

                    <img src="{{ asset('images/ricky.jpeg') }}"
                         class="w-32 h-40 object-cover rounded-2xl mx-auto border-4 border-white shadow-lg"
                         alt="Foto Ricky Yordani">

                    <h3 class="mt-5 text-2xl font-bold text-[#1A237E]">
                        Ricky Yordani, M.Stat.
                    </h3>

                    <span
                        class="inline-block mt-2 px-4 py-1 bg-blue-100 text-[#1A237E] text-sm font-medium rounded-full">
                        Dosen Pembimbing
                    </span>

                    <p class="mt-5 text-gray-600 leading-relaxed">
                        Memberikan arahan metodologi penelitian,
                        evaluasi hasil analisis, serta supervisi akademik
                        selama pelaksanaan penelitian.
                    </p>

                </div>
            </div>

        </div>

    </div>
</section>

<!-- ================= LOKASI PENELITIAN ================= -->
<section id="lokasi-penelitian" class="py-10 md:py-16 bg-white">
  <div class="max-w-7xl mx-auto px-6">

    <h3 class="text-3xl font-bold mb-8 text-[#1A237E] text-center">
      LOKASI PENELITIAN
    </h3>

    <div class="flex flex-col md:flex-row gap-10  items-center">

      <!-- MAP SHP -->
      <div id="map-klaten" class="md:w-1/2 w-full h-80 md:h-[400px] rounded-xl shadow-md"></div>

      <!-- SMALL CARD (UPDATED) -->
      <div class="md:w-1/2 flex flex-col gap-4 ">

        <!-- Card Lokasi Penelitian -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-[#1A237E] ">
          <h4 class="text-[#1A237E] font-semibold mb-2 text-base">
            Informasi Lokasi
          </h4>
          <p>
            Penelitian dilakukan di Kabupaten Klaten dengan unit analisis desa/kelurahan.
            Fokus pada distribusi potensi pengembangan pariwisata berbasis indeks menggunakan integrasi data Point of Interest (POI) Google Maps dan Official Statistics.
          </p>
        </div>

        <!-- Card Tambahan Insight -->
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 text-sm text-gray-700">
          <h4 class="text-[#1A237E] font-semibold mb-2 text-base">
            Catatan Analisis
          </h4>
          <p>
            Pendekatan spasial digunakan untuk mengidentifikasi ketimpangan persebaran potensi pengembangan pariwisata antar wilayah.
          </p>
        </div>

      </div>

    </div>
  </div>
</section>

<!-- ================= STAKEHOLDER ================= -->
<section id="stakeholder" class="py-20 bg-blue-50">
  <div class="max-w-7xl mx-auto px-6 ">

    <h2 class="text-3xl font-bold text-center text-[#1A237E] mb-12">
      STAKEHOLDER PENELITIAN
    </h2>

    <div class="flex flex-col md:flex-row gap-10  items-center">

      <!-- MAP -->
      <div class="md:w-1/2 h-96 rounded-2xl overflow-hidden shadow">
        <div id="map-stakeholder" class="w-full h-full"></div>
      </div>

      <!-- CARD STAKEHOLDER (NEW DESIGN) -->
      <div class="md:w-1/2">

        <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-[#1A237E]">

          <h3 class="text-xl font-semibold text-[#1A237E] mb-2">
            Disbudporapar Kabupaten Klaten
          </h3>

          <p class="text-gray-700 mb-4 text-sm">
            Instansi pemerintah yang berperan sebagai sumber data utama dalam penelitian ini serta pengelola sektor pariwisata daerah.
          </p>

          <div class="bg-blue-50 rounded-xl p-4 text-sm text-gray-700">
            <p>📧 info@disbudpar.klaten.go.id</p>
            <p>🌐 disbudpar.klaten.go.id</p>
            <p>📷 @disbudporapar.klaten</p>
          </div>

        </div>

      </div>

    </div>
  </div>
</section>

<!-- ================= INSTITUSI AKADEMIK ================= -->
<section id="institusi" class="py-20 bg-white">
  <div class="max-w-6xl mx-auto px-6">

    <h2 class="text-3xl font-bold text-[#1A237E] mb-12 text-center">
      INSTITUSI AKADEMIK
    </h2>

    <div class="flex flex-col md:flex-row items-center gap-10">

      <div class="md:w-1/2">
        <img 
          src="https://www.datapendidikan.com/blog/wp-content/uploads/2023/12/Politeknik-Statistika-STIS-1024x576.jpeg"
          class="w-full h-[300px] object-cover rounded-2xl shadow-lg"
        >
      </div>

      <div class="md:w-1/2">
        <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-[#1A237E]">
          <h3 class="text-xl font-semibold text-[#1A237E] mb-3">
            Politeknik Statistika STIS
          </h3>

          <p class="text-gray-700 leading-relaxed">
            Politeknik Statistika STIS merupakan perguruan tinggi kedinasan di bawah naungan lembaga Badan Pusat Statistik (BPS) yang berfokus pada program studi statistika dan komputasi statistik.
          </p>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- ================= LEAFLET ================= -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {

  // ================= MAP SHP KLATEN =================
  const mapKlaten = L.map('map-klaten').setView([-7.6876, 110.6085], 11);

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap'
  }).addTo(mapKlaten);

  function cap(str){
    return (str || '').toLowerCase().replace(/\b\w/g, c => c.toUpperCase());
  }

  fetch("{{ asset('data/desa-klaten.geojson') }}")
    .then(r => r.json())
    .then(data => {
      const layer = L.geoJSON(data, {
        style: {
          color: "#1A237E",
          weight: 1,
          fillColor: "#3F51B5",
          fillOpacity: 0.4
        },
        onEachFeature: (f, l) => {
          l.bindPopup(
            `<b>Desa:</b> ${cap(f.properties.nmdesa)}<br>
             <b>Kecamatan:</b> ${cap(f.properties.nmkec)}`
          );
        }
      }).addTo(mapKlaten);

      if (layer.getBounds().isValid()) {
        mapKlaten.fitBounds(layer.getBounds(), { padding:[20,20], maxZoom:15 });
      }
    });


  // ================= MAP STAKEHOLDER =================
  const mapStakeholder = L.map('map-stakeholder').setView([-7.6876, 110.6085], 14);

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap'
  }).addTo(mapStakeholder);

  L.marker([-7.6876, 110.6085])
    .addTo(mapStakeholder)
    .bindPopup("<b>Disbudporapar Klaten</b>")
    .openPopup();

});
</script>
@endpush

@endsection