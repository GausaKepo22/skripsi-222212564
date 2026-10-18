# Analisis Spasial Potensi Pengembangan Pariwisata Menggunakan Integrasi Data Point of Interest dan Official Statistics (Studi Kasus: Kabupaten Klaten, Jawa Tengah)

Repositori ini berisi kode sumber aplikasi web dashboard interaktif yang dibangun menggunakan framework **Laravel** untuk memvisualisasikan analisis spasial dan pemetaan potensi pengembangan pariwisata di tingkat desa/kelurahan di Kabupaten Klaten, Jawa Tengah.

## Fitur Utama
- **Dashboard Interaktif**: Visualisasi indikator komposit potensi pengembangan pariwisata per desa/kelurahan.
- **Peta Spasial (Leaflet.js)**: Peta tematik distribusi Point of Interest (POI) dan statistik resmi.
- **Analisis Autokorelasi Spasial**: Visualisasi statistik Global Moran's I dan Local Indicators of Spatial Association (LISA).

## Persyaratan Sistem
- PHP >= 8.2
- Composer
- Node.js & NPM
- Web Server (Laragon / Apache / Nginx)

## Cara Menginstal & Menjalankan Proyek Lokal

**Clone Repositori:**
git clone [https://git.stis.ac.id/222212564/analisis-spasial-potensi-pengembangan-pariwisata-menggunakan-integrasi-data-point-of-interest-dan-official-statistics-studi-kasus-kabupaten-klaten-jawa-tengah.git](https://git.stis.ac.id/222212564/analisis-spasial-potensi-pengembangan-pariwisata-menggunakan-integrasi-data-point-of-interest-dan-official-statistics-studi-kasus-kabupaten-klaten-jawa-tengah.git)
cd skripsi

1. **Install Dependensi PHP & JavaScript:**
composer install
npm install

2. **Konfigurasi Environment:**
cp .env.example .env
php artisan key:generate

3. **Jalankan Aplikasi:**
npm run dev
php artisan serve

Akses aplikasi melalui browser di http://127.0.0.1:8000.

Penulis / Peneliti: Dewi Nurihandayani (NIM: 222212564)
Institusi: Politeknik Statistika STIS