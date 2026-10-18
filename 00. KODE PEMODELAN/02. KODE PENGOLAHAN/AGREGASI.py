import pandas as pd
import numpy as np
import requests
import time

stasiun_path = '/content/drive/MyDrive/Skripsi/04. PRE-PROCESSING/STASIUN'
df_stasiun = pd.read_excel(stasiun_path)
df_stasiun.head()

# Pastikan kolom koordinat bertipe numerik
df['LATITUDE'] = pd.to_numeric(df['LATITUDE'], errors='coerce')
df['LONGITUDE'] = pd.to_numeric(df['LONGITUDE'], errors='coerce')
df_stasiun['Latitude'] = pd.to_numeric(df_stasiun['Latitude'], errors='coerce')
df_stasiun['Longitude'] = pd.to_numeric(df_stasiun['Longitude'], errors='coerce')

# Fungsi untuk menghitung jarak jalan menggunakan OSRM API
def jarak_jalan_osrm(lat1, lon1, lat2, lon2):
    if pd.isna(lat1) or pd.isna(lon1) or pd.isna(lat2) or pd.isna(lon2):
        return None

    url = f"http://router.project-osrm.org/route/v1/driving/{lon1},{lat1};{lon2},{lat2}"
    params = {"overview": "false"}

    try:
        r = requests.get(url, params=params, timeout=10)
        data = r.json()

        if data.get("code") == "Ok":
            return data["routes"][0]["distance"] / 1000
        else:
            return None
    except:
        return None

# Fungsi untuk mencari stasiun terdekat dari suatu titik koordinat
def cari_stasiun_terdekat(lat, lon, df_stasiun):
    if pd.isna(lat) or pd.isna(lon):
        return pd.Series([None, None])

    jarak_min = None
    nama_stasiun_terdekat = None

    for _, s in df_stasiun.iterrows():
        jarak = jarak_jalan_osrm(lat, lon, s['Latitude'], s['Longitude'])

        if jarak is not None:
            if jarak_min is None or jarak < jarak_min:
                jarak_min = jarak
                nama_stasiun_terdekat = s['Nama']
        time.sleep(0.1)

    return pd.Series([jarak_min, nama_stasiun_terdekat])

# Hitung jarak dan tentukan nama stasiun terdekat
df[['jarak_stasiun_terdekat_km', 'stasiun_terdekat']] = df.apply(
    lambda x: cari_stasiun_terdekat(x['LATITUDE'], x['LONGITUDE'], df_stasiun), axis=1
)

df.head()

# Simpan hasil perhitungan jarak stasiun
output_path = '/content/drive/MyDrive/Skripsi/04. PRE-PROCESSING/DATA DESA_GEOTAG.xlsx'
df.to_excel(output_path, index=False)
print("File berhasil disimpan:", output_path)


# HITUNG WEIGHTED RATE
file_path = '/content/drive/My Drive/Skripsi/04. PRE-PROCESSING/DTW'
df = pd.read_excel(file_path)

print("===== DATA AWAL =====")
df.head()

# Konversi rating dan ulasan ke numerik (kosong menjadi NaN atau 0)
df['RATING'] = pd.to_numeric(df['RATING'], errors='coerce')
df['ULASAN'] = pd.to_numeric(df['ULASAN'], errors='coerce').fillna(0)

print("\n===== CEK DATA NUMERIK =====")
print(df[['RATING', 'ULASAN']].head())

# Hitung parameter global (C = mean rating global, m = median ulasan)
C = df['RATING'].mean(skipna=True)
m = df['ULASAN'].median()

print("\n===== PARAMETER GLOBAL =====")
print("C (Mean Rating Global) :", round(C, 4))
print("m (Median Ulasan)      :", round(m, 4))

# Hitung Weighted Rating (WR)
df['WR'] = np.where(
    df['RATING'].notna(),
    ((df['ULASAN'] / (df['ULASAN'] + m)) * df['RATING'] + (m / (df['ULASAN'] + m)) * C),
    np.nan
)

# Normalisasi WR ke skala 0-100
wr_min = df['WR'].min()
wr_max = df['WR'].max()
df['WR_100'] = ((df['WR'] - wr_min) / (wr_max - wr_min)) * 100

# Urutkan berdasarkan Weighted Rating tertinggi
df = df.sort_values(by='WR', ascending=False)
df.head()

print("Jumlah baris awal:", len(df))
print("Kolom:", df.columns)

# Pastikan tipe data numerik sebelum agregasi
df['WR'] = pd.to_numeric(df['WR'], errors='coerce')
df['WR_100'] = pd.to_numeric(df['WR_100'], errors='coerce')
df['JARAK_STASIUN'] = pd.to_numeric(df['JARAK_STASIUN'], errors='coerce')

# Agregasi data wisata per desa
agg_desa = df.groupby(['KECAMATAN', 'DESA']).agg(
    TOTAL_WISATA=('NAMA', 'count'),
    MEAN_DTW_STASIUN=('JARAK_STASIUN', 'mean'),
    MEAN_WR=('WR', 'mean'),
    MEAN_WR_100=('WR_100', 'mean')
).reset_index()

# Hitung jumlah wisata per kategori dengan pivot
kategori_pivot = pd.pivot_table(
    df, index=['KECAMATAN', 'DESA'], columns='KATEGORI', values='NAMA', aggfunc='count', fill_value=0
)
kategori_pivot.columns = [f"JUMLAH_{col}" for col in kategori_pivot.columns]
kategori_pivot = kategori_pivot.reset_index()

# Gabungkan hasil agregasi dasar dan jumlah per kategori
final_table = pd.merge(agg_desa, kategori_pivot, on=['KECAMATAN', 'DESA'], how='left')
final_table = final_table.sort_values(by=['KECAMATAN', 'DESA']).reset_index(drop=True)

print("Baris Hasil Agregasi", len(final_table))
final_table.head()

# Simpan hasil agregasi DTW dan WR
output_path = '/content/drive/My Drive/Skripsi/04. PRE-PROCESSING/DTW_WR.xlsx'
final_table.to_excel(output_path, index=False)
print("File berhasil disimpan di:", output_path)


# AGREGASI FILE (KCDA, ATM, DTW)
kcda_file_path = '/content/drive/MyDrive/Skripsi/04. PRE-PROCESSING/01. DATA DESA.xlsx'
atm_file_path = '/content/drive/MyDrive/Skripsi/04. PRE-PROCESSING/02. ATM.xlsx'
dtw_file_path = '/content/drive/MyDrive/Skripsi/04. PRE-PROCESSING/03. DTW_WR.xlsx'

kcda = pd.read_excel(kcda_file_path)
atm = pd.read_excel(atm_file_path)
dtw = pd.read_excel(dtw_file_path)

kcda.head()
atm.head()
dtw.head()

# Gabungkan data ATM dengan data desa (KCDA), lalu gabungkan dengan data wisata (DTW)
df_joined = pd.merge(kcda, atm, on=['KECAMATAN', 'DESA'], how='outer')
df_joined = pd.merge(df_joined, dtw, on=['KECAMATAN', 'DESA'], how='outer')

# Isi nilai NaN dengan 0 untuk kolom numerik
for col in df_joined.columns:
    if col not in ['KECAMATAN', 'DESA'] and pd.api.types.is_numeric_dtype(df_joined[col]):
        df_joined[col] = df_joined[col].fillna(0)

print("Dimensi df_joined:", df_joined.shape)
print("\nHead of df_joined:")
df_joined.head()

# Simpan hasil agregasi final
output_path = '/content/drive/My Drive/Skripsi/04. PRE-PROCESSING/AGREGASI.xlsx'
df_joined.to_excel(output_path, index=False)
print("File berhasil disimpan di:", output_path)