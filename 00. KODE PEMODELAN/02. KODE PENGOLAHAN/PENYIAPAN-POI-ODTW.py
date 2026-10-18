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
base_path = '/content/drive/MyDrive/Skripsi/02. HASIL SCRAPING/00. MENTAH/03. Kode Pengolahan/'

path_mentah  = base_path + 'extension_not_docker.xlsx'
path_unik    = base_path + 'extension_not_docker_UNIK.xlsx'
path_coord   = base_path + 'extension_not_docker_DENGAN_KOORDINAT.xlsx'
path_shp     = base_path + 'SHP/ADMINISTRASIDESA_AR_25K.shp'
path_final   = base_path + 'POI_dengan_Administrasi_Desa.xlsx'

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
def extract_lat_lon(link):
    if pd.isna(link):
        return np.nan, np.nan
    # Mencari pola koordinat di URL (menggunakan format @lat,lon)
    match = re.search(r'@(-?\d+\.\d+),(-?\d+\.\d+)', str(link))
    if match:
        return float(match.group(1)), float(match.group(2))
    return np.nan, np.nan

# Aplikasikan fungsi ke kolom 'link'
df_unique[['latitude', 'longitude']] = df_unique['link'].apply(
    lambda x: pd.Series(extract_lat_lon(x))
)

total_data = len(df_unique)
berhasil_geocode = df_unique['latitude'].notna().sum()
gagal_geocode = total_data - berhasil_geocode

print(f"Total data        : {total_data}")
print(f"Berhasil geocode  : {berhasil_geocode}")
print(f"Gagal geocode     : {gagal_geocode}")

# Filter hanya data yang memiliki koordinat untuk proses spasial selanjutnya
df_coord = df_unique[df_unique['latitude'].notna()].copy()

# Simpan checkpoint tahap 2
df_coord.to_excel(path_coord, index=False)
print(f"File dengan koordinat disimpan di: {path_coord}\n")

# ==============================================================================
# TAHAP 3: SPATIAL JOIN DENGAN SHAPEFILE (SHP) DESA
# ==============================================================================
print("--- TAHAP 3: SPATIAL JOIN (POINT IN POLYGON) ---")
# Buat GeoDataFrame dari titik koordinat
gdf_poi = gpd.GeoDataFrame(
    df_coord,
    geometry=gpd.points_from_xy(df_coord['longitude'], df_coord['latitude']),
    crs="EPSG:4326" # CRS standar WGS84 untuk koordinat scraping
)
print("POI berhasil dikonversi ke GeoDataFrame.")

# Baca data poligon batas administrasi desa (SHP)
gdf_desa = gpd.read_file(path_shp)
print(f"Shapefile desa berhasil dibaca. Jumlah poligon desa: {len(gdf_desa)}")

# Pastikan CRS shapefile sama dengan data titik
if gdf_desa.crs != gdf_poi.crs:
    gdf_desa = gdf_desa.to_crs(gdf_poi.crs)
print(f"CRS POI diselaraskan. CRS Desa saat ini: {gdf_desa.crs}")

# Lakukan Spatial Join
gdf_join = gpd.sjoin(
    gdf_poi,
    gdf_desa,
    how="left",
    predicate="within"
)
print("Spatial join selesai.\n")

# Hapus kolom dari SHP/Join yang tidak diperlukan jika ada (sesuaikan dengan isi atribut SHP)
kolom_hapus = ['geometry', 'index_right']
df_final = gdf_join.drop(columns=[col for col in kolom_hapus if col in gdf_join.columns], errors='ignore')

# ==============================================================================
# TAHAP 4: EVALUASI & EKSPOR HASIL AKHIR
# ==============================================================================
print("--- TAHAP 4: EVALUASI & EKSPOR ---")
total_final = len(df_final)

# Asumsikan kolom nama desa di SHP bernama 'NAMOBJ' (Berdasarkan format RBI BIG standar). 
# GANTI 'NAMOBJ' dengan nama kolom desa yang benar dari atribut SHP Anda jika berbeda.
kolom_desa = 'NAMOBJ' if 'NAMOBJ' in df_final.columns else df_final.columns[-1] 

terdeteksi = df_final[kolom_desa].notna().sum()
tdk_terdeteksi = total_final - terdeteksi

print(f"Total POI dengan koordinat : {total_final}")
print(f"Berhasil masuk wilayah     : {terdeteksi}")
print(f"Gagal masuk wilayah        : {tdk_terdeteksi} (Titik berada di luar poligon SHP)")

# Pisahkan data yang gagal dan berhasil ter-join spasial
df_gagal = df_final[df_final[kolom_desa].isna()]
df_berhasil = df_final[df_final[kolom_desa].notna()].copy()

# Plot untuk melihat sebaran titik yang gagal (berada di luar poligon)
if tdk_terdeteksi > 0:
    print("\nVisualisasi titik yang gagal masuk poligon batas administrasi:")
    fig, ax = plt.subplots(figsize=(8, 8))
    gdf_desa.plot(ax=ax, color='white', edgecolor='black', linewidth=0.5)
    gdf_poi[gdf_poi.index.isin(df_gagal.index)].plot(ax=ax, color='red', markersize=10, label='Di Luar Batas')
    plt.title("Titik Gagal Spatial Join")
    plt.legend()
    plt.show()

# Simpan hasil akhir
df_final.to_excel(path_final, index=False)
print(f"✅ Eksekusi Selesai! File hasil integrasi (beserta yang gagal & berhasil) diekspor ke:\n{path_final}")