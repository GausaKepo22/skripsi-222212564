!pip install geopandas

# Import library yang dibutuhkan
import pandas as pd
import geopandas as gpd
import numpy as np
import matplotlib.pyplot as plt

from google.colab import drive
from shapely.geometry import Point
from scipy.spatial import distance_matrix
from scipy.stats import norm

# Mount Google Drive
drive.mount('/content/drive')

# Load data excel wisata
excel_path = '/content/drive/My Drive/Skripsi/08. ANN/DTW.xlsx'
df = pd.read_excel(excel_path)
print(df.head())

# Cek dan hapus data koordinat yang kosong
print(df[['LATITUDE', 'LONGITUDE']].head())
df = df.dropna(subset=['LATITUDE', 'LONGITUDE'])

# Ubah DataFrame menjadi GeoDataFrame
geometry = [Point(xy) for xy in zip(df['LONGITUDE'], df['LATITUDE'])]
gdf_wisata = gpd.GeoDataFrame(df, geometry=geometry, crs='EPSG:4326')
print(gdf_wisata.head())

# Load shapefile desa
shp_path = '/content/drive/My Drive/Skripsi/08. ANN/SHP/Desa_Geo_3310.shp'
gdf_desa = gpd.read_file(shp_path)

# Cek CRS SHP dan set jika None
print("CRS SHP:\n", gdf_desa.crs)
if gdf_desa.crs is None:
    gdf_desa = gdf_desa.set_crs(epsg=4326)

# Ubah ke CRS Meter (wajib untuk analisis ANN)
gdf_wisata = gdf_wisata.to_crs(epsg=32749)
gdf_desa = gdf_desa.to_crs(epsg=32749)

# Ambil koordinat X dan Y
gdf_wisata['X'] = gdf_wisata.geometry.x
gdf_wisata['Y'] = gdf_wisata.geometry.y
print(gdf_wisata[['NAMA', 'X', 'Y']].head())

# Membuat array koordinat
coords = np.array(list(zip(gdf_wisata['X'], gdf_wisata['Y'])))

# Hitung distance matrix dan hapus jarak ke diri sendiri
dist_matrix = distance_matrix(coords, coords)
np.fill_diagonal(dist_matrix, np.inf)

# Cari tetangga terdekat dan hitung Observed Mean Distance
nearest_dist = dist_matrix.min(axis=1)
observed_mean_distance = nearest_dist.mean()
print("Observed Mean Distance:", observed_mean_distance)

# Hitung luas area studi dan jumlah titik wisata
study_area = gdf_desa.unary_union.area
print("Study Area:", study_area)

n = len(gdf_wisata)
print("Jumlah Wisata:", n)

# Hitung Expected Mean Distance, ANN Ratio, dan Standard Error
expected_mean_distance = 0.5 / np.sqrt(n / study_area)
print("Expected Mean Distance:", expected_mean_distance)

ann_ratio = observed_mean_distance / expected_mean_distance
print("ANN Ratio:", ann_ratio)

standard_error = 0.26136 / np.sqrt((n**2) / study_area)

# Hitung Z-Score dan P-Value
z_score = (observed_mean_distance - expected_mean_distance) / standard_error
print("Z-Score:", z_score)

p_value = 2 * (1 - norm.cdf(abs(z_score)))
print("P-Value:", p_value)

# Interpretasi Hasil ANN Keseluruhan
print("\n====================================================")
print("            HASIL ANALISIS ANN")
print("====================================================")

# Kecenderungan Pola (Berdasarkan ANN Ratio)
if ann_ratio < 1:
    kecenderungan = "Cenderung Mengelompok"
elif ann_ratio > 1:
    kecenderungan = "Cenderung Menyebar"
else:
    kecenderungan = "Cenderung Acak"

# Interpretasi Signifikansi (Berdasarkan Z-Score dengan α = 0.05)
if z_score < -1.96:
    pola = "Clustered Signifikan"
    interpretasi = "Pola persebaran wisata mengelompok dan signifikan secara statistik."
elif z_score > 1.96:
    pola = "Dispersed Signifikan"
    interpretasi = "Pola persebaran wisata menyebar dan signifikan secara statistik."
else:
    pola = "Random / Tidak Signifikan"
    interpretasi = "Pola persebaran wisata tidak berbeda signifikan dari pola acak."

# Output Hasil
print(f"ANN Ratio  : {ann_ratio:.4f}")
print(f"Z-Score    : {z_score:.4f}")
print(f"P-Value    : {p_value:.6f}")
print("----------------------------------------------------")
print(f"Kecenderungan Pola : {kecenderungan}")
print(f"Hasil Akhir        : {pola}")
print("----------------------------------------------------")
print("Interpretasi:\n", interpretasi)
print("====================================================\n")

# Visualisasi Peta
fig, ax = plt.subplots(figsize=(12, 12))
gdf_desa.plot(ax=ax, color='white', edgecolor='black', linewidth=0.5)
gdf_wisata.plot(ax=ax, markersize=20, color='red')
plt.title('Pola Sebaran Wisata - Average Nearest Neighbor')
plt.xlabel('X Coordinate')
plt.ylabel('Y Coordinate')
plt.show()

# Simpan hasil sementara
hasil_ann = pd.DataFrame({
    'Observed_Mean_Distance': [observed_mean_distance],
    'Expected_Mean_Distance': [expected_mean_distance],
    'ANN_Ratio': [ann_ratio],
    'Z_Score': [z_score],
    'P_Value': [p_value],
    'Pattern': [pola]
})


# =========================================================
# ANALISIS ANN PER KATEGORI WISATA
# =========================================================

print("\nDaftar Kategori Wisata:\n", gdf_wisata['KATEGORI'].unique())

hasil_kategori = []

# Loop analisis untuk setiap kategori wisata
for kategori in gdf_wisata['KATEGORI'].unique():
    print(f"\n====================================================")
    print(f"Kategori Wisata : {kategori}")
    print("====================================================")

    # Filter data berdasarkan kategori
    gdf_kat = gdf_wisata[gdf_wisata['KATEGORI'] == kategori].copy()
    n = len(gdf_kat)
    print(f"Jumlah Wisata : {n}")

    # ANN minimal membutuhkan 2 titik
    if n < 2:
        print("Data tidak cukup untuk analisis ANN")
        continue

    # Hitung matriks jarak per kategori
    coords = np.array(list(zip(gdf_kat['X'], gdf_kat['Y'])))
    dist_matrix = distance_matrix(coords, coords)
    np.fill_diagonal(dist_matrix, np.inf)

    # Cari tetangga terdekat & parameter ANN
    nearest_dist = dist_matrix.min(axis=1)
    observed_mean_distance = nearest_dist.mean()
    expected_mean_distance = 0.5 / np.sqrt(n / study_area)
    ann_ratio = observed_mean_distance / expected_mean_distance
    standard_error = 0.26136 / np.sqrt((n**2) / study_area)
    z_score = (observed_mean_distance - expected_mean_distance) / standard_error
    p_value = 2 * (1 - norm.cdf(abs(z_score)))

    # Interpretasi pola per kategori
    if ann_ratio < 1:
        kecenderungan = "Cenderung Mengelompok"
    elif ann_ratio > 1:
        kecenderungan = "Cenderung Menyebar"
    else:
        kecenderungan = "Cenderung Acak"

    if z_score < -1.96:
        pola = "Clustered Signifikan"
        interpretasi = "Pola persebaran wisata mengelompok dan signifikan secara statistik."
    elif z_score > 1.96:
        pola = "Dispersed Signifikan"
        interpretasi = "Pola persebaran wisata menyebar dan signifikan secara statistik."
    else:
        pola = "Random / Tidak Signifikan"
        interpretasi = "Pola persebaran wisata tidak berbeda signifikan dari pola acak."

    # Output hasil kategori
    print(f"Observed Mean Distance : {observed_mean_distance:.2f} meter")
    print(f"Expected Mean Distance : {expected_mean_distance:.2f} meter")
    print(f"ANN Ratio              : {ann_ratio:.4f}")
    print(f"Z-Score                : {z_score:.4f}")
    print(f"P-Value                : {p_value:.6f}")
    print("----------------------------------------------------")
    print(f"Kecenderungan Pola     : {kecenderungan}")
    print(f"Hasil Akhir            : {pola}")
    print("----------------------------------------------------")
    print("Interpretasi:\n", interpretasi)
    print("====================================================")

    # Simpan hasil per kategori ke list
    hasil_kategori.append({
        'Kategori': kategori,
        'Jumlah_Wisata': n,
        'Observed_Mean_Distance': round(observed_mean_distance, 2),
        'Expected_Mean_Distance': round(expected_mean_distance, 2),
        'ANN_Ratio': round(ann_ratio, 4),
        'Z_Score': round(z_score, 4),
        'P_Value': round(p_value, 6),
        'Kecenderungan_Pola': kecenderungan,
        'Hasil_Akhir': pola,
        'Interpretasi': interpretasi
    })

# Ubah hasil kategori menjadi DataFrame
df_ann_kategori = pd.DataFrame(hasil_kategori)
print("\n===== HASIL ANN PER KATEGORI =====")
display(df_ann_kategori)

# Visualisasi per kategori
for kategori in gdf_wisata['KATEGORI'].unique():
    gdf_kat = gdf_wisata[gdf_wisata['KATEGORI'] == kategori]
    if len(gdf_kat) < 2:
        continue

    fig, ax = plt.subplots(figsize=(10, 10))
    gdf_desa.plot(ax=ax, color='white', edgecolor='black', linewidth=0.5)
    gdf_kat.plot(ax=ax, markersize=25)
    plt.title(f'ANN Wisata Kategori: {kategori}')
    plt.xlabel('X Coordinate')
    plt.ylabel('Y Coordinate')
    plt.show()

# =========================================================
# GABUNGKAN HASIL KESELURUHAN & PER KATEGORI
# =========================================================

# Variabel overall diambil dari proses ANN keseluruhan di atas sebelum loop kategori
if ann_ratio < 1:  # Note: menggunakan variabel dari block 31 kode asli
    kecenderungan_overall = "Cenderung Mengelompok"
elif ann_ratio > 1:
    kecenderungan_overall = "Cenderung Menyebar"
else:
    kecenderungan_overall = "Cenderung Acak"

if z_score < -1.96:
    pola_overall = "Clustered Signifikan"
    interpretasi_overall = "Pola persebaran wisata mengelompok dan signifikan secara statistik."
elif z_score > 1.96:
    pola_overall = "Dispersed Signifikan"
    interpretasi_overall = "Pola persebaran wisata menyebar dan signifikan secara statistik."
else:
    pola_overall = "Random / Tidak Signifikan"
    interpretasi_overall = "Pola persebaran wisata tidak berbeda signifikan dari pola acak."

hasil_ann_overall = pd.DataFrame({
    'Kategori': ['Keseluruhan'],
    'Jumlah_Wisata': [len(gdf_wisata)],
    'Observed_Mean_Distance': [round(hasil_ann['Observed_Mean_Distance'][0], 2)],
    'Expected_Mean_Distance': [round(hasil_ann['Expected_Mean_Distance'][0], 2)],
    'ANN_Ratio': [round(hasil_ann['ANN_Ratio'][0], 4)],
    'Z_Score': [round(hasil_ann['Z_Score'][0], 4)],
    'P_Value': [round(hasil_ann['P_Value'][0], 6)],
    'Kecenderungan_Pola': [kecenderungan_overall],
    'Hasil_Akhir': [pola_overall],
    'Interpretasi': [interpretasi_overall]
})

# Gabungkan hasil dan simpan ke Excel
df_ann_final = pd.concat([hasil_ann_overall, df_ann_kategori], ignore_index=True)
print("\n===== HASIL ANN KESELURUHAN ====")
display(df_ann_final)

output_final = '/content/drive/My Drive/Skripsi/08. ANN/Hasil_ANN.xlsx'
df_ann_final.to_excel(output_final, index=False)