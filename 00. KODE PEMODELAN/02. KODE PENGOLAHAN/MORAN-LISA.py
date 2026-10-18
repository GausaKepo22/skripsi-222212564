import warnings
warnings.filterwarnings('ignore')

import pandas as pd
import geopandas as gpd
import numpy as np
import matplotlib.pyplot as plt
import json
import os
import matplotlib.colors as mcolors

from google.colab import drive

# Spatial Statistics
from libpysal.weights import Queen
from esda.moran import Moran, Moran_Local

# Visualization
from splot.esda import moran_scatterplot

# Mount Google Drive
drive.mount('/content/drive')

# Load data
excel_path = '/content/drive/My Drive/Skripsi/09. MORAN/Hasil_Indeks.xlsx'
shp_path = '/content/drive/My Drive/Skripsi/09. MORAN/SHP/Desa_Geo_3310.shp'

df_excel = pd.read_excel(excel_path)
gdf_shp = gpd.read_file(shp_path)

# Cek Data
print("="*60)
print("INFO DATA")
print("="*60)
print("\nJumlah data Excel :", len(df_excel))
print("Jumlah data SHP   :", len(gdf_shp))
print("\nKolom Excel:\n", df_excel.columns)
print("\nKolom SHP:\n", gdf_shp.columns)

# Samakan format kolom key
key_cols = ['kdprov', 'kdkab', 'kdkec', 'kddesa']

for col in key_cols:
    df_excel[col] = df_excel[col].astype(str).str.strip()
    gdf_shp[col] = gdf_shp[col].astype(str).str.strip()

# Merge data
gdf = gdf_shp.merge(df_excel, on=['nmkec', 'nmdesa'], how='inner')
print("\nJumlah data setelah merge :", len(gdf))

# Cek duplikat
duplicate_count = gdf.duplicated(subset=['nmkec','nmdesa']).sum()
print("\nJumlah duplikat :", duplicate_count)

# Cek & Set CRS
print("\nCRS Awal:\n", gdf.crs)
if gdf.crs is None:
    gdf = gdf.set_crs(epsg=4326)
print("\nCRS Setelah Set:\n", gdf.crs)

# Proyeksi ke UTM
gdf = gdf.to_crs(epsg=32749)
print("\nCRS Setelah Proyeksi:\n", gdf.crs)

# Cek & Hapus Missing Value
print("\nMissing Value Indeks_Entropy :", gdf['Indeks_Entropy'].isna().sum())
gdf = gdf.dropna(subset=['Indeks_Entropy'])

# Deskripsi Statistik
print("\n" + "="*60 + "\nSTATISTIK DESKRIPTIF\n" + "="*60)
print(gdf['Indeks_Entropy'].describe())

# Membuat Spatial Weight (Queen Contiguity)
w = Queen.from_dataframe(gdf)
w.transform = 'R'

# Informasi Spatial Weight
print("\n" + "="*60 + "\nSPATIAL WEIGHT\n" + "="*60)
print("\nJumlah Observasi :", w.n)
print("Jumlah Total Neighbor :", w.s0)
print("Jumlah Island :", len(w.islands))
print("Daftar Island :\n", w.islands)

# Visualisasi Neighbor
fig, ax = plt.subplots(figsize=(12,10))
gdf.plot(ax=ax, color='lightgrey', edgecolor='black', linewidth=0.3)
centroids = gdf.centroid

for i, neighbors in w.neighbors.items():
    for neighbor in neighbors:
        x = [centroids.iloc[i].x, centroids.iloc[neighbor].x]
        y = [centroids.iloc[i].y, centroids.iloc[neighbor].y]
        ax.plot(x, y, color='red', linewidth=0.3)

ax.set_title('Visualisasi Spatial Neighbor', fontsize=14)
ax.axis('off')
plt.show()

# Export Queen Neighbors ke JSON
neighbors_export = {int(key): [int(v) for v in value] for key, value in w.neighbors.items()}
output_path = '/content/drive/My Drive/Skripsi/09. MORAN/neighbors.json'

with open(output_path, 'w') as f:
    json.dump(neighbors_export, f, indent=4)

print("Neighbors berhasil diekspor ke:\n", output_path)

# Variabel Analisis
y = gdf['Indeks_Entropy'].values

# Global Moran's I
moran = Moran(y, w)

# Hasil Global Moran
print("\n" + "="*60 + "\nGLOBAL MORAN'S I\n" + "="*60)
print(f"\nMoran's I        : {moran.I:.6f}")
print(f"Expected I       : {moran.EI:.6f}")
print(f"Variance         : {moran.VI_norm:.6f}")
print(f"Z-Score          : {moran.z_norm:.6f}")
print(f"P-Value          : {moran.p_norm:.6f}")

# Interpretasi Global Moran
print("\n" + "="*60 + "\nINTERPRETASI\n" + "="*60)
alpha = 0.05
if moran.p_norm < alpha:
    print("\nHasil signifikan (p < 0.05)")
    if moran.I > 0:
        print("\nTerdapat autokorelasi spasial positif.")
        print("Wilayah dengan indeks tinggi cenderung berdekatan dengan wilayah indeks tinggi.")
        print("Wilayah dengan indeks rendah cenderung berdekatan dengan wilayah indeks rendah.")
        print("\nPola spasial bersifat MENGELOMPOK (CLUSTERED).")
    elif moran.I < 0:
        print("\nTerdapat autokorelasi spasial negatif.")
        print("Wilayah tinggi cenderung berdekatan dengan wilayah rendah.")
        print("\nPola spasial bersifat MENYEBAR (DISPERSED).")
else:
    print("\nTidak terdapat autokorelasi spasial yang signifikan.")
    print("Pola spasial bersifat ACAK.")

# Moran Scatterplot
fig, ax = plt.subplots(figsize=(8, 8))
moran_scatterplot(moran, ax=ax)
ax.set_title("Moran Scatterplot", fontsize=14, fontweight="bold")
plt.tight_layout()
plt.show()

# Standardisasi Z-Score & Spatial Lag
y_z = (y - y.mean()) / y.std()
spatial_lag = w.sparse.dot(y)
spatial_lag_z = w.sparse.dot(y_z)

# DataFrame Hasil Analisis
df_result = gdf[['nmkec', 'nmdesa', 'Indeks_Entropy']].copy()
df_result['Spatial Lag'] = spatial_lag
df_result['Z-Score'] = y_z
df_result['Spatial Lag Z-Score'] = spatial_lag_z

print("\n" + "="*60 + "\nDATAFRAME HASIL ANALISIS\n" + "="*60)
print(df_result.head())

# Ekspor DataFrame ke Excel
output_df_result_excel = '/content/drive/My Drive/Skripsi/09. MORAN/df_result_spatial_lag.xlsx'
df_result.to_excel(output_df_result_excel, index=False)
print("\nDataFrame 'df_result' berhasil diekspor ke Excel:\n", output_df_result_excel)

import seaborn as sns

# Global Moran's I & Simulasi Monte Carlo
moran_global = Moran(y, w, permutations=999)

# Tabel Ringkasan Uji Monte Carlo Global
global_summary = pd.DataFrame({
    'Parameter': [
        'Jumlah Permutasi (N)', 'Indeks Moran Observasi (I)', 'Ekspektasi Simulasi E(I)',
        'Standard Deviation (SD)', 'z-score (Simulasi)', 'Pseudo p-value'
    ],
    'Nilai': [
        moran_global.permutations, round(moran_global.I, 4), round(moran_global.EI_sim, 4),
        round(moran_global.seI_sim, 4), round(moran_global.z_sim, 4), moran_global.p_sim
    ]
})

print("\n" + "="*60 + "\nTABEL RINGKASAN UJI MONTE CARLO GLOBAL MORAN'S I\n" + "="*60)
print(global_summary.to_string(index=False))

# Histogram Simulasi Monte Carlo Global
fig, ax = plt.subplots(figsize=(10, 6))
sns.histplot(moran_global.sim, kde=True, color='lightgrey', edgecolor='black', ax=ax)
ax.axvline(moran_global.I, color='red', linestyle='--', linewidth=2, label=f'Observed I ({moran_global.I:.4f})')
ax.axvline(moran_global.EI_sim, color='darkblue', linestyle=':', linewidth=4, label=f'Expected E(I) ({moran_global.EI_sim:.4f})')

ax.set_title('Histogram Simulasi Monte Carlo (Global Moran\'s I)', fontsize=14, fontweight='bold')
ax.set_xlabel('Indeks Moran (Permutasi)', fontsize=12)
ax.set_ylabel('Frekuensi', fontsize=12)
ax.legend(fontsize=11)
plt.tight_layout()
plt.show()

# Local Moran's I (LISA)
lisa = Moran_Local(y, w, permutations=999, seed=123)

gdf['Local_I'] = lisa.Is
gdf['Local_p'] = lisa.p_sim
gdf['Z_Score'] = lisa.z_sim
gdf['Quadrant'] = lisa.q

# Klasifikasi Klaster LISA
def lisa_cluster(row, alpha=0.05):
    if row['Local_p'] > alpha:
        return 'Not Significant'
    elif row['Quadrant'] == 1:
        return 'High-High'
    elif row['Quadrant'] == 2:
        return 'Low-High'
    elif row['Quadrant'] == 3:
        return 'Low-Low'
    elif row['Quadrant'] == 4:
        return 'High-Low'
    else:
        return 'Undefined'

gdf['LISA_Cluster'] = gdf.apply(lisa_cluster, axis=1)

# Tabel Ringkasan Signifikansi Monte Carlo Lokal
def p_value_category(p):
    if p < 0.001:
        return 'p < 0.001 (Sangat Signifikan)'
    elif p < 0.01:
        return '0.001 <= p < 0.01 (Signifikan Tinggi)'
    elif p < 0.05:
        return '0.01 <= p < 0.05 (Signifikan)'
    else:
        return 'p >= 0.05 (Tidak Signifikan)'

gdf['p_category'] = gdf['Local_p'].apply(p_value_category)
p_summary = gdf['p_category'].value_counts().reset_index()
p_summary.columns = ['Interval Pseudo p-value (Monte Carlo)', 'Jumlah Wilayah']
p_summary['Persentase (%)'] = round((p_summary['Jumlah Wilayah'] / len(gdf)) * 100, 2)

print("\n" + "="*60 + "\nTABEL RINGKASAN HASIL SIMULASI MONTE CARLO (LISA)\n" + "="*60)
print(p_summary.to_string(index=False))

# Visualisasi Peta Signifikansi
def sig_map_label(p):
    if p < 0.001: return 'p < 0.001'
    elif p < 0.01: return 'p < 0.01'
    elif p < 0.05: return 'p < 0.05'
    else: return 'Not Significant'

gdf['Sig_Level'] = gdf['Local_p'].apply(sig_map_label)

sig_colors = {
    'p < 0.001': '#00441b', 'p < 0.01': '#2a924a', 'p < 0.05': '#a1d99b', 'Not Significant': '#d9d9d9'
}

fig, ax = plt.subplots(figsize=(10, 8))
for category, color in sig_colors.items():
    sub_gdf = gdf[gdf['Sig_Level'] == category]
    if not sub_gdf.empty:
        sub_gdf.plot(ax=ax, color=color, label=category, edgecolor='black', linewidth=0.3)

ax.set_title('Peta Signifikansi LISA (Simulasi Monte Carlo)', fontsize=14, fontweight='bold')
ax.axis('off')
ax.legend(title="Tingkat Signifikansi", loc='lower right', frameon=True)
plt.tight_layout()
plt.show()

# Visualisasi Peta Klaster LISA
cluster_colors = {
    'High-High': '#d7191c', 'Low-Low': '#2b83ba', 'Low-High': '#abdda4',
    'High-Low': '#fdae61', 'Not Significant': '#e0e0e0'
}

fig, ax = plt.subplots(figsize=(10, 8))
for cluster, color in cluster_colors.items():
    sub_gdf = gdf[gdf['LISA_Cluster'] == cluster]
    if not sub_gdf.empty:
        sub_gdf.plot(ax=ax, color=color, label=f"{cluster} ({len(sub_gdf)})", edgecolor='black', linewidth=0.3)

ax.set_title('Peta Klaster Spasial LISA (Hasil Filter Monte Carlo)', fontsize=14, fontweight='bold')
ax.axis('off')
ax.legend(title="Tipe Klaster Spasial", loc='lower right', frameon=True)
plt.tight_layout()
plt.show()

# Parameter Simulasi Monte Carlo (LISA)
lisa_param_table = pd.DataFrame({
    'Parameter Simulasi': [
        'Metode Pengujian', 'Jumlah Permutasi (N)', 'Random Seed', 'Tingkat Signifikansi (Alpha)',
        'Total Wilayah Diamati (n)', 'Jumlah Wilayah Signifikan (p < 0.05)', 'Jumlah Wilayah Tidak Signifikan (p >= 0.05)'
    ],
    'Nilai / Keterangan': [
        'Local Monte Carlo Conditional Permutation', lisa.permutations, 123, 0.05,
        len(gdf), (gdf['Local_p'] < 0.05).sum(), (gdf['Local_p'] >= 0.05).sum()
    ]
})

print("\n" + "="*60 + "\nTABEL PARAMETER SIMULASI MONTE CARLO (LISA)\n" + "="*60)
print(lisa_param_table.to_string(index=False))

# Distribusi Pseudo p-value Monte Carlo
p_bins = [0, 0.001, 0.01, 0.05, 1.0]
p_labels = [
    'p < 0.001 (Sangat Signifikan)', '0.001 <= p < 0.01 (Signifikan Tinggi)',
    '0.01 <= p < 0.05 (Signifikan)', 'p >= 0.05 (Tidak Signifikan)'
]
gdf['p_group'] = pd.cut(gdf['Local_p'], bins=p_bins, labels=p_labels, include_lowest=True)

lisa_p_table = gdf['p_group'].value_counts().reset_index()
lisa_p_table.columns = ['Klasifikasi Pseudo p-value (Monte Carlo)', 'Jumlah Wilayah']
lisa_p_table['Persentase (%)'] = round((lisa_p_table['Jumlah Wilayah'] / len(gdf)) * 100, 2)

print("\n" + "="*60 + "\nTABEL DISTRIBUSI SIGNIFIKANSI MONTE CARLO LOKAL\n" + "="*60)
print(lisa_p_table.to_string(index=False))

# Menyusun Tabel Hasil LISA Final Per Wilayah
df_lisa_final = pd.DataFrame()
df_lisa_final['ID_Desa'] = gdf['iddesa_x']
df_lisa_final['Kecamatan'] = gdf['nmkec']
df_lisa_final['Desa'] = gdf['nmdesa']
df_lisa_final['Nilai_Variabel'] = gdf['Indeks_Entropy']
df_lisa_final['Nilai_Lokal_I'] = round(gdf['Local_I'], 4)
df_lisa_final['Z_Score'] = round(gdf['Z_Score'], 4)
df_lisa_final['Pseudo_P_Value'] = round(gdf['Local_p'], 4)

df_lisa_final['Signifikansi'] = np.where(gdf['Local_p'] < 0.05, 'Signifikan', 'Tidak Signifikan')
quadrant_map = {1: 'High-High', 2: 'Low-High', 3: 'Low-Low', 4: 'High-Low'}
df_lisa_final['Kuadran_Moran'] = gdf['Quadrant'].map(quadrant_map)
df_lisa_final['Klaster_LISA_Final'] = gdf['LISA_Cluster']

# Mengurutkan data
df_lisa_final = df_lisa_final.sort_values(by=['Pseudo_P_Value', 'Kecamatan', 'Desa'], ascending=[True, True, True])

# Ekspor Hasil LISA Final
output_excel_final = '/content/drive/My Drive/Skripsi/09. MORAN/Hasil_LISA_Final_Wilayah.xlsx'
df_lisa_final.to_excel(output_excel_final, index=False)

print("\n" + "="*60 + "\nHASIL LISA FINAL BERHASIL DIEKSPOR SEBAGAI TABEL WILAYAH\n" + "="*60)
print(f"File tersimpan di: {output_excel_final}")
print("\nPratinjau 5 Data Pertama:\n", df_lisa_final.head().to_string(index=False))

# Tabel Wilayah High-High & Low-Low
hh = gdf[gdf['LISA_Cluster'] == 'High-High']
print("\n" + "="*60 + "\nWILAYAH HIGH-HIGH\n" + "="*60)
print(hh[['nmkec','nmdesa','Indeks_Entropy']].sort_values(by='Indeks_Entropy', ascending=False))

ll = gdf[gdf['LISA_Cluster'] == 'Low-Low']
print("\n" + "="*60 + "\nWILAYAH LOW-LOW\n" + "="*60)
print(ll[['nmkec','nmdesa','Indeks_Entropy']].sort_values(by='Indeks_Entropy', ascending=True))

# Ekspor Excel, SHP, GeoJSON
output_excel = '/content/drive/My Drive/Skripsi/09. MORAN/Hasil_Moran_Indeks.xlsx'
gdf.drop(columns='geometry').to_excel(output_excel, index=False)
print("\nHasil Excel berhasil disimpan\n", output_excel)

output_shp = '/content/drive/My Drive/Skripsi/09. MORAN/Hasil_Moran_Indeks.shp'
gdf.to_file(output_shp)
print("\nShapefile berhasil disimpan\n", output_shp)

output_geojson = '/content/drive/My Drive/Skripsi/09. MORAN/Hasil_Moran_Indeks.geojson'
gdf.to_file(output_geojson, driver='GeoJSON')
print("\nGeoJSON berhasil disimpan\n", output_geojson)

# Ringkasan Akhir
print("\n" + "="*70 + "\nRINGKASAN ANALISIS SPASIAL\n" + "="*70)
print(f"\nJumlah Wilayah : {len(gdf)}")
print(f"\nNilai Moran's I : {moran.I:.6f}")
print(f"P-Value         : {moran.p_norm:.6f}")

if moran.p_norm < 0.05:
    pola = "Clustered (Mengelompok)" if moran.I > 0 else "Dispersed (Menyebar)"
else:
    pola = "Random (Acak)"

print(f"\nPola Spasial : {pola}")
print("\nJumlah Cluster:\n", gdf['LISA_Cluster'].value_counts())

# Kesimpulan
print("\n" + "="*70 + "\nKESIMPULAN PENELITIAN\n" + "="*70)
if moran.p_norm < 0.05:
    if moran.I > 0:
        print("\nIndeks potensi pengembangan pariwisata menunjukkan autokorelasi spasial positif yang signifikan.")
        print("Pola spasial bersifat mengelompok (clustered).")
    else:
        print("\nIndeks menunjukkan autokorelasi spasial negatif yang signifikan.")
        print("Pola spasial bersifat menyebar (dispersed).")
else:
    print("\nTidak ditemukan autokorelasi spasial yang signifikan.")
    print("Pola persebaran indeks potensi pengembangan pariwisata bersifat acak.")

# Ekspor Daftar Desa Berdasarkan Tingkat Signifikansi Monte Carlo
def klasifikasi_signifikansi_detail(p):
    if p <= 0.001: return 'Sangat Signifikan (p <= 0.001)'
    elif p <= 0.01: return 'Signifikan Tinggi (0.001 < p <= 0.01)'
    elif p < 0.05: return 'Signifikan (0.01 < p < 0.05)'
    else: return 'Tidak Signifikan (p >= 0.05)'

df_lisa_final['Kategori_Signifikansi'] = df_lisa_final['Pseudo_P_Value'].apply(klasifikasi_signifikansi_detail)

kolom_urut = [
    'ID_Desa', 'Kecamatan', 'Desa', 'Nilai_Variabel', 'Nilai_Lokal_I',
    'Z_Score', 'Pseudo_P_Value', 'Kategori_Signifikansi', 'Klaster_LISA_Final'
]
df_sig_export = df_lisa_final[kolom_urut].sort_values(by=['Pseudo_P_Value', 'Kecamatan', 'Desa'], ascending=[True, True, True])

output_excel_sig = '/content/drive/My Drive/Skripsi/09. MORAN/Daftar_Wilayah_Signifikansi_MonteCarlo.xlsx'

with pd.ExcelWriter(output_excel_sig, engine='openpyxl') as writer:
    df_sig_export.to_excel(writer, sheet_name='Semua Wilayah', index=False)
    
    df_sangat_sig = df_sig_export[df_sig_export['Kategori_Signifikansi'] == 'Sangat Signifikan (p <= 0.001)']
    if not df_sangat_sig.empty: df_sangat_sig.to_excel(writer, sheet_name='Sangat Signifikan', index=False)
    
    df_sig_tinggi = df_sig_export[df_sig_export['Kategori_Signifikansi'] == 'Signifikan Tinggi (0.001 < p <= 0.01)']
    if not df_sig_tinggi.empty: df_sig_tinggi.to_excel(writer, sheet_name='Signifikan Tinggi', index=False)
    
    df_sig = df_sig_export[df_sig_export['Kategori_Signifikansi'] == 'Signifikan (0.01 < p < 0.05)']
    if not df_sig.empty: df_sig.to_excel(writer, sheet_name='Signifikan', index=False)
    
    df_tidak_sig = df_sig_export[df_sig_export['Kategori_Signifikansi'] == 'Tidak Signifikan (p >= 0.05)']
    if not df_tidak_sig.empty: df_tidak_sig.to_excel(writer, sheet_name='Tidak Signifikan', index=False)

print("\n" + "="*70 + "\nDAFTAR WILAYAH BERDASARKAN SIGNIFIKANSI BERHASIL DIEKSPOR\n" + "="*70)
print(f"File tersimpan di: {output_excel_sig}")
print(f"Terdapat {len(pd.ExcelFile(output_excel_sig).sheet_names)} sheet di dalam file Excel tersebut.")