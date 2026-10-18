# ==============================================================================
# PERSIAPAN & INSTALASI (Jalankan di Google Colab)
# ==============================================================================
!pip install geopandas shapely pyproj fiona

from google.colab import drive
import pandas as pd
import geopandas as gpd
import numpy as np
import re
import matplotlib.pyplot as plt

# Mount Google Drive (cukup dipanggil satu kali)
drive.mount('/content/drive')

# ==============================================================================
# PENGATURAN PATH FILE
# ==============================================================================
base_path_mentah = "/content/drive/MyDrive/Skripsi/02. HASIL SCRAPING/00. MENTAH/04. ATM dan BRILink/00. REKAP/"
base_path_bersih = "/content/drive/MyDrive/Skripsi/02. HASIL SCRAPING/01. BERSIH/"

path_mentah = base_path_mentah + "ATM dan BRILink.xlsx"
path_unik   = base_path_mentah + "ATM_dan_BRILink_UNIK.xlsx"
path_coord  = base_path_mentah + "ATM_dan_BRILink_DENGAN_KOORDINAT.xlsx"
path_shp    = base_path_bersih + "SHP/final_desa_3310_2025_1.shp"
path_final  = base_path_bersih + "ATM_dan_BRILink_BERHASIL_DESA_KECAMATAN.xlsx"

# ==============================================================================
# TAHAP 1: MEMBERSIHKAN DATA DUPLIKAT
# ==============================================================================
print("--- TAHAP 1: PEMBERSIHAN DATA DUPLIKAT ---")
df = pd.read_excel(path_mentah)
print(f"Jumlah baris awal: {len(df)}")

duplikat = df[df.duplicated(keep=False)]
print(f"Jumlah baris duplikat yang terdeteksi: {len(duplikat)}")

df_unique = df.drop_duplicates(keep='first').copy()
print(f"Jumlah baris setelah dibersihkan: {len(df_unique)}")

# Simpan checkpoint tahap 1
df_unique.to_excel(path_unik, index=False)
print(f"File unik disimpan di: {path_unik}\n")

# ==============================================================================
# TAHAP 2: EKSTRAKSI KOORDINAT DARI LINK
# ==============================================================================
print("--- TAHAP 2: EKSTRAKSI KOORDINAT (LATITUDE & LONGITUDE) ---")
def extract_lat_lon(url):
    if pd.isna(url):
        return np.nan, np.nan
    # Mencari pola koordinat di URL Google Maps (!3d... !4d...)
    match = re.search(r'!3d(-?\d+\.\d+)!4d(-?\d+\.\d+)', url)
    if match:
        return float(match.group(1)), float(match.group(2))
    return np.nan, np.nan

# Aplikasikan fungsi ke kolom 'Link'
df_unique[['latitude', 'longitude']] = df_unique['Link'].apply(
    lambda x: pd.Series(extract_lat_lon(x))
)

total_data = len(df_unique)
berhasil = df_unique['latitude'].notna().sum()
gagal = total_data - berhasil

print(f"Total data        : {total_data}")
print(f"Berhasil geocode  : {berhasil}")
print(f"Gagal geocode     : {gagal}")

# Simpan checkpoint tahap 2
df_unique.to_excel(path_coord, index=False)
print(f"File dengan koordinat disimpan di: {path_coord}\n")

# ==============================================================================
# TAHAP 3: SPATIAL JOIN DENGAN SHAPEFILE (SHP) DESA
# ==============================================================================
print("--- TAHAP 3: SPATIAL JOIN (POINT IN POLYGON) ---")
# Buat GeoDataFrame dari titik koordinat
gdf_point = gpd.GeoDataFrame(
    df_unique,
    geometry=gpd.points_from_xy(df_unique['longitude'], df_unique['latitude']),
    crs="EPSG:4326"
)

# Baca data poligon batas desa (SHP)
gdf_desa = gpd.read_file(path_shp)

# Pastikan CRS shapefile sama dengan data titik (WGS 84 / EPSG:4326)
if gdf_desa.crs != "EPSG:4326":
    gdf_desa = gdf_desa.to_crs(epsg=4326) # Gunakan to_crs agar transformasi koordinat tepat

# Lakukan Spatial Join
gdf_join = gpd.sjoin(
    gdf_point,
    gdf_desa,
    how="left",
    predicate="within"
)

# Sesuaikan nama kolom wilayah
gdf_join['Desa_Kelurahan'] = gdf_join['nmdesa']
gdf_join['Kecamatan'] = gdf_join['nmkec']
gdf_join['Kabupaten'] = gdf_join['nmkab']
gdf_join['Provinsi'] = gdf_join['nmprov']

# Hapus kolom dari SHP yang tidak diperlukan
kolom_hapus = ['gid', 'luas', 'kdkab', 'kdkec', 'iddesa', 'kddesa', 
               'kdprov', 'rw_dki', 'sumber', 'periode', 'geometry', 'index_right',
               'nmdesa', 'nmkec', 'nmkab', 'nmprov']
df_final = gdf_join.drop(columns=kolom_hapus, errors='ignore')

# ==============================================================================
# TAHAP 4: EVALUASI & EKSPOR HASIL AKHIR
# ==============================================================================
print("--- TAHAP 4: EVALUASI & EKSPOR ---")
total_final = len(df_final)
terdeteksi = df_final['Desa_Kelurahan'].notna().sum()
tdk_terdeteksi = total_final - terdeteksi

print(f"Total data          : {total_final}")
print(f"Berhasil terdeteksi : {terdeteksi}")
print(f"Gagal terdeteksi    : {tdk_terdeteksi} (Titik berada di luar poligon SHP)")

# Pisahkan data yang gagal dan berhasil
df_gagal = df_final[df_final['Desa_Kelurahan'].isna()]
df_berhasil = df_final[df_final['Desa_Kelurahan'].notna()].copy()

# Plot untuk melihat sebaran titik yang gagal (berada di luar poligon)
if tdk_terdeteksi > 0:
    print("\nVisualisasi titik yang gagal masuk poligon batas administrasi:")
    fig, ax = plt.subplots(figsize=(8, 8))
    gdf_desa.plot(ax=ax, color='white', edgecolor='black')
    gdf_point[gdf_point.index.isin(df_gagal.index)].plot(ax=ax, color='red', markersize=10)
    plt.title("Titik Gagal Spatial Join (Di Luar Batas)")
    plt.show()

# Simpan hasil akhir yang sukses
df_berhasil.to_excel(path_final, index=False)
print(f"✅ Eksekusi Selesai! File bersih diekspor ke: {path_final}")