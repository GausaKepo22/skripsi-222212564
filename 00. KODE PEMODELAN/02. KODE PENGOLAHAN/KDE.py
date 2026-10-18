!pip install geopandas rasterio contextily scikit-learn -q

# Import library yang dibutuhkan
import pandas as pd
import geopandas as gpd
import numpy as np
import matplotlib.pyplot as plt
import rasterio

from google.colab import drive
from shapely.geometry import Point
from sklearn.neighbors import KernelDensity
from rasterio.transform import from_origin

# Mount Google Drive
drive.mount('/content/drive')

# Load data excel DTW
excel_path = '/content/drive/My Drive/Skripsi/10. KDE/DTW.xlsx'
df = pd.read_excel(excel_path)
print("Jumlah data awal :", len(df))
print(df.head())

# Cek struktur data dan koordinat
print("\nKolom Data:\n", df.columns)
print("\nInformasi Data:\n", df.info())
print("\nContoh koordinat:\n", df[['NAMA', 'LATITUDE', 'LONGITUDE']].head())

# Hapus data koordinat kosong
df = df.dropna(subset=['LATITUDE', 'LONGITUDE'])
print("\nJumlah data setelah hapus koordinat kosong :", len(df))

# Membuat GeoDataFrame DTW
geometry = [Point(xy) for xy in zip(df['LONGITUDE'], df['LATITUDE'])]
gdf_wisata = gpd.GeoDataFrame(df, geometry=geometry, crs='EPSG:4326')
print("\nGeoDataFrame berhasil dibuat")
print(gdf_wisata.head())

# Load shapefile desa
shp_path = '/content/drive/My Drive/Skripsi/10. KDE/SHP/Desa_Geo_3310.shp'
gdf_desa = gpd.read_file(shp_path)
print("\nJumlah desa :", len(gdf_desa))

# Cek dan set CRS Shapefile jika kosong
print("\nCRS SHP:\n", gdf_desa.crs)
if gdf_desa.crs is None:
    gdf_desa = gdf_desa.set_crs(epsg=4326)

# Ubah CRS ke sistem meter (wajib untuk KDE dan analisis spasial)
gdf_wisata = gdf_wisata.to_crs(epsg=32749)
gdf_desa = gdf_desa.to_crs(epsg=32749)
print("\nCRS setelah reproyeksi:\n", gdf_wisata.crs)

# Ambil koordinat X dan Y
gdf_wisata['X'] = gdf_wisata.geometry.x
gdf_wisata['Y'] = gdf_wisata.geometry.y
print("\nKoordinat hasil reproyeksi:\n", gdf_wisata[['NAMA', 'X', 'Y']].head())

# Visualisasi titik DTW
fig, ax = plt.subplots(figsize=(10, 10))
gdf_desa.boundary.plot(ax=ax, linewidth=0.5, color='black')
gdf_wisata.plot(ax=ax, color='red', markersize=20, alpha=0.7)
ax.set_title('Persebaran Daya Tarik Wisata', fontsize=14)
ax.set_axis_off()
plt.show()

# Persiapan data KDE
coords = np.vstack([gdf_wisata['X'], gdf_wisata['Y']]).T
print("\nJumlah titik wisata :", len(coords))

# Menentukan bandwidth KDE
bandwidth = 1000
print("\nBandwidth KDE :", bandwidth, "meter")

# Membangun model KDE
kde = KernelDensity(bandwidth=bandwidth, kernel='gaussian')
kde.fit(coords)
print("\nModel KDE berhasil dibuat")

# Membuat grid analisis
xmin, ymin, xmax, ymax = gdf_desa.total_bounds
print("\nBatas wilayah:\n", xmin, ymin, xmax, ymax)

cell_size = 100
xgrid = np.arange(xmin, xmax, cell_size)
ygrid = np.arange(ymin, ymax, cell_size)
xx, yy = np.meshgrid(xgrid, ygrid)
grid_coords = np.vstack([xx.ravel(), yy.ravel()]).T

# Hitung nilai KDE
log_density = kde.score_samples(grid_coords)
density = np.exp(log_density)
density_matrix = density.reshape(xx.shape)
print("\nKDE selesai dihitung")

# Visualisasi hasil KDE (background putih)
from shapely.geometry import mapping
from rasterio.features import geometry_mask
from rasterio.transform import from_origin

fig, ax = plt.subplots(figsize=(12, 12))
fig.patch.set_facecolor('white')
ax.set_facecolor('white')

# Transform raster dan mask batas wilayah
transform = from_origin(xmin, ymax, cell_size, cell_size)
wilayah = gdf_desa.unary_union

mask = geometry_mask([mapping(wilayah)], transform=transform, invert=True, out_shape=density_matrix.shape)
mask = np.flipud(mask)  # Balik mask agar sesuai imshow(origin='lower')
density_clipped = np.where(mask, density_matrix, np.nan)

# Tampilkan KDE
im = ax.imshow(density_clipped, origin='lower', extent=(xmin, xmax, ymin, ymax), cmap='hot', alpha=0.7)
gdf_desa.boundary.plot(ax=ax, linewidth=0.4, color='black')
gdf_wisata.plot(ax=ax, color='blue', markersize=10)

cbar = plt.colorbar(im, ax=ax)
cbar.set_label('Kepadatan Wisata', fontsize=11)
ax.set_title('Kernel Density Estimation (KDE)\nPersebaran Daya Tarik Wisata', fontsize=15)
ax.set_xlim(xmin, xmax)
ax.set_ylim(ymin, ymax)
ax.set_axis_off()
plt.show()

# Simpan hasil KDE menjadi raster TIFF
output_raster = '/content/drive/My Drive/Skripsi/10. KDE/Hasil_KDE_DTW.tif'
transform = from_origin(xmin, ymax, cell_size, cell_size)

with rasterio.open(
    output_raster,
    'w',
    driver='GTiff',
    height=density_matrix.shape[0],
    width=density_matrix.shape[1],
    count=1,
    dtype=density_matrix.dtype,
    crs=gdf_desa.crs,
    transform=transform
) as dst:
    dst.write(density_matrix, 1)

print("\nRaster KDE berhasil disimpan")
print(output_raster)

# Statistik eksploratif KDE
print("\nSTATISTIK KDE")
print("="*40)
print("Nilai minimum :", density.min())
print("Nilai maksimum :", density.max())
print("Nilai rata-rata :", density.mean())
print("Nilai median :", np.median(density))


# =========================================================
# KDE PER KATEGORI WISATA
# =========================================================

kategori_list = gdf_wisata['KATEGORI'].dropna().unique()
print("\nDaftar Kategori Wisata:\n", kategori_list)

# Looping KDE setiap kategori
for kategori in kategori_list:
    print("\n" + "="*60)
    print(f"KATEGORI : {kategori}")
    print("="*60)

    gdf_kategori = gdf_wisata[gdf_wisata['KATEGORI'] == kategori]
    print("Jumlah titik wisata :", len(gdf_kategori))

    # Skip jika data terlalu sedikit
    if len(gdf_kategori) < 3:
        print("Data terlalu sedikit untuk KDE")
        continue

    coords_kategori = np.vstack([gdf_kategori['X'], gdf_kategori['Y']]).T

    kde_kategori = KernelDensity(bandwidth=bandwidth, kernel='gaussian')
    kde_kategori.fit(coords_kategori)

    log_density_kategori = kde_kategori.score_samples(grid_coords)
    density_kategori = np.exp(log_density_kategori)
    density_matrix_kategori = density_kategori.reshape(xx.shape)

    # Statistik KDE per kategori
    print("\nSTATISTIK KDE")
    print("Minimum Density :", density_kategori.min())
    print("Maximum Density :", density_kategori.max())
    print("Mean Density :", density_kategori.mean())
    print("Median Density :", np.median(density_kategori))
    print("Std. Deviation :", density_kategori.std())

    # Clip dengan SHP dan visualisasi
    density_clipped_kategori = np.where(mask, density_matrix_kategori, np.nan)

    fig, ax = plt.subplots(figsize=(12, 12))
    fig.patch.set_facecolor('white')
    ax.set_facecolor('white')

    im = ax.imshow(density_clipped_kategori, origin='lower', extent=(xmin, xmax, ymin, ymax), cmap='hot', alpha=0.7)
    gdf_desa.boundary.plot(ax=ax, linewidth=0.4, color='black')
    gdf_kategori.plot(ax=ax, color='blue', markersize=12)

    cbar = plt.colorbar(im, ax=ax)
    cbar.set_label('Kepadatan Wisata', fontsize=11)
    ax.set_title(f'KDE Persebaran Wisata\nKategori: {kategori}', fontsize=15)
    ax.set_xlim(xmin, xmax)
    ax.set_ylim(ymin, ymax)
    ax.set_axis_off()
    plt.show()

# Import ulang (sesuai urutan kode awal)
import matplotlib.pyplot as plt
import numpy as np
from sklearn.neighbors import KernelDensity
from rasterio.transform import from_origin
from shapely.geometry import mapping
from rasterio.features import geometry_mask

# KDE per kategori wisata (Dibuat ulang untuk memastikan visualisasi lengkap)
kategori_list = gdf_wisata['KATEGORI'].dropna().unique()
print("\nDaftar Kategori Wisata:\n", kategori_list)

# Looping KDE untuk setiap kategori (Iterasi ke-2)
for kategori in kategori_list:
    print("\n" + "="*60)
    print(f"KATEGORI : {kategori}")
    print("="*60)

    gdf_kategori = gdf_wisata[gdf_wisata['KATEGORI'] == kategori]
    print("Jumlah titik wisata :", len(gdf_kategori))

    if len(gdf_kategori) < 3:
        print("Data terlalu sedikit untuk KDE (minimum 3 titik). Melewatkan kategori ini.")
        continue

    coords_kategori = np.vstack([gdf_kategori['X'], gdf_kategori['Y']]).T

    kde_kategori = KernelDensity(bandwidth=bandwidth, kernel='gaussian')
    kde_kategori.fit(coords_kategori)

    log_density_kategori = kde_kategori.score_samples(grid_coords)
    density_kategori = np.exp(log_density_kategori)
    density_matrix_kategori = density_kategori.reshape(xx.shape)

    print("\nSTATISTIK KDE")
    print("Minimum Density :", density_kategori.min())
    print("Maximum Density :", density_kategori.max())
    print("Mean Density :", density_kategori.mean())
    print("Median Density :", np.median(density_kategori))
    print("Std. Deviation :", density_kategori.std())

    density_clipped_kategori = np.where(mask, density_matrix_kategori, np.nan)

    fig, ax = plt.subplots(figsize=(12, 12))
    fig.patch.set_facecolor('white')
    ax.set_facecolor('white')

    im = ax.imshow(density_clipped_kategori, origin='lower', extent=(xmin, xmax, ymin, ymax), cmap='hot', alpha=0.7)
    gdf_desa.boundary.plot(ax=ax, linewidth=0.4, color='black')
    gdf_kategori.plot(ax=ax, color='blue', markersize=12)

    cbar = plt.colorbar(im, ax=ax)
    cbar.set_label('Kepadatan Wisata', fontsize=11)
    ax.set_title(f'KDE Persebaran Wisata\nKategori: {kategori}', fontsize=15)
    ax.set_xlim(xmin, xmax)
    ax.set_ylim(ymin, ymax)
    ax.set_axis_off()
    plt.show()