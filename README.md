```markdown
# Analisis Spasial Potensi Pengembangan Pariwisata Menggunakan Integrasi Data Point of Interest dan Official Statistics (Studi Kasus: Kabupaten Klaten, Jawa Tengah)

Repositori ini berisi seluruh kode, data, dan dokumentasi untuk analisis spasial potensi pengembangan pariwisata di Kabupaten Klaten, Jawa Tengah. Proyek ini terdiri dari dua bagian utama: **Pemodelan Spasial** (menggunakan Python) dan **Aplikasi Web Dashboard** (menggunakan Laravel).

---

## BAGIAN 1: PEMODELAN SPASIAL

Bagian ini berfokus pada identifikasi dan pemetaan potensi pengembangan pariwisata melalui pendekatan analisis spasial, dengan menggabungkan data persebaran titik lokasi (Point of Interest) dan data statistik resmi (Official Statistics).

### Struktur Direktori Pemodelan
Seluruh dataset dan kode pemodelan analisis spasial menggunakan Python disimpan di dalam folder khusus `00. KODE PEMODELAN/`:

```text
00. KODE PEMODELAN/
├── 01. BATAS ADMINISTRASI/
├── 02. KODE PENGOLAHAN/
│   ├── AGREGASI.py
│   ├── ANN.py
│   ├── KDE.py
│   ├── KONSTRUKSI-INDEKS.py
│   ├── MORAN-LISA.py
│   ├── PENYIAPAN-POI-ATM.py
│   └── PENYIAPAN-POI-ODTW.py
└── 03. DATASET/

```

### Metodologi Pemodelan

Pengolahan data spasial pada folder `02. KODE PENGOLAHAN/` dilakukan melalui tahapan berikut:

1. **Preprocessing (Penyiapan & Agregasi Data)**: Penyiapan data spasial dan atribut pendukung menggunakan `PENYIAPAN-POI-ATM.py`, `PENYIAPAN-POI-ODTW.py`, dan `AGREGASI.py`.
2. **Average Nearest Neighbor (ANN)**: Analisis pola persebaran titik POI (mengelompok, acak, atau menyebar) menggunakan `ANN.py`.
3. **Kernel Density Estimation (KDE)**: Estimasi kepadatan POI secara spasial menggunakan `KDE.py`.
4. **Konstruksi Indeks Komposit**: Pembobotan indikator untuk menyusun indeks komposit potensi pariwisata menggunakan `KONSTRUKSI-INDEKS.py`.
5. **Analisis Autokorelasi Spasial**: Analisis kecenderungan pengelompokan menggunakan Global Moran's I dan LISA melalui `MORAN-LISA.py`.

### Persyaratan Sistem (Pemodelan)

* Python 3.9+
* Library: `geopandas`, `pandas`, `numpy`, `pysal`/`esda`, `libpysal`, `scipy`, `matplotlib`
* QGIS/ArcGIS (opsional, untuk pemeriksaan visual shapefile)

---

## BAGIAN 2: APLIKASI WEB DASHBOARD

Bagian ini merupakan kode sumber aplikasi web interaktif yang dibangun menggunakan framework **Laravel**. Aplikasi ini berfungsi untuk memvisualisasikan hasil dari pemodelan spasial agar lebih mudah dipahami dan diakses oleh pengguna.

### Fitur Utama Dashboard

* **Dashboard Interaktif**: Visualisasi indikator komposit potensi pengembangan pariwisata per desa/kelurahan.
* **Peta Spasial (Leaflet.js)**: Peta tematik distribusi Point of Interest (POI) dan official statistics.
* **Analisis Autokorelasi Spasial**: Visualisasi statistik Global Moran's I dan Local Indicators of Spatial Association (LISA) ke dalam bentuk peta.

### Struktur Direktori Web Aplikasi

Struktur direktori di level utama mengikuti format standar framework Laravel:

```text
.
├── app/
├── bootstrap/
├── config/
├── database/
├── public/
├── resources/
├── routes/
├── storage/
├── tests/
├── vendor/
├── .env.example
├── composer.json
├── package.json
└── README.md

```

### Persyaratan Sistem (Dashboard)

* PHP >= 8.2
* Composer
* Node.js & NPM
* Web Server (Laragon / Apache / Nginx)

### Cara Menginstal & Menjalankan Dashboard Lokal

**1. Clone Repositori:**

```bash
git clone [https://git.stis.ac.id/222212564/analisis-spasial-potensi-pengembangan-pariwisata-menggunakan-integrasi-data-point-of-interest-dan-official-statistics-studi-kasus-kabupaten-klaten-jawa-tengah.git](https://git.stis.ac.id/222212564/analisis-spasial-potensi-pengembangan-pariwisata-menggunakan-integrasi-data-point-of-interest-dan-official-statistics-studi-kasus-kabupaten-klaten-jawa-tengah.git)
cd skripsi

```

**2. Install Dependensi PHP & JavaScript:**

```bash
composer install
npm install

```

**3. Konfigurasi Environment:**

```bash
cp .env.example .env
php artisan key:generate

```

**4. Jalankan Aplikasi:**

```bash
npm run dev
php artisan serve

```

Akses aplikasi web melalui browser di **http://127.0.0.1:8000**.

---

## Lisensi

[Tentukan lisensi proyek Anda di sini, misal: MIT License]

## Penulis / Peneliti

**Dewi Nurihandayani** (NIM: 222212564)

Institusi: Politeknik Statistika STIS