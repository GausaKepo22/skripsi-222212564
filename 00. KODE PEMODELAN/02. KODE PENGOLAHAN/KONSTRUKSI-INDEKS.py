import pandas as pd
import geopandas as gpd
from shapely.geometry import Point
from google.colab import drive

# Mount Google Drive
drive.mount('/content/drive')

# Load dataset utama
excel_file_path = '/content/drive/MyDrive/Skripsi/05. PENGOLAHAN/VARIABEL9.xlsx'
df = pd.read_excel(excel_file_path)

df.head()

# Eksplorasi data awal
print(f"Jumlah baris: {df.shape[0]}")
print(f"Jumlah kolom: {df.shape[1]}")
print("\nDeskripsi statistik kolom numerik:")
print(df.describe())
print("Jumlah missing value per kolom:")
print(df.isnull().sum())

num_duplicates = df.duplicated().sum()
if num_duplicates > 0:
    print(f"Terdapat {num_duplicates} baris duplikat dalam DataFrame.")
else:
    print("Tidak ada baris duplikat dalam DataFrame.")

numerical_cols = df.select_dtypes(include=['number']).columns

# Pengecekan Outlier (Metode IQR)
print("Pengecekan Outlier (metode IQR):")
for col in numerical_cols:
    Q1 = df[col].quantile(0.25)
    Q3 = df[col].quantile(0.75)
    IQR = Q3 - Q1
    lower_bound = Q1 - 1.5 * IQR
    upper_bound = Q3 + 1.5 * IQR

    outliers = df[(df[col] < lower_bound) | (df[col] > upper_bound)]
    num_outliers = len(outliers)

    if num_outliers > 0:
        print(f"- Kolom '{col}': {num_outliers} outlier ditemukan.")
    else:
        print(f"- Kolom '{col}': Tidak ada outlier.")


import matplotlib.pyplot as plt
import seaborn as sns

# Visualisasi distribusi data setiap variabel numerik
numerical_cols = df.select_dtypes(include=['number']).columns
num_cols = len(numerical_cols)
num_rows = (num_cols + 2) // 3

plt.figure(figsize=(18, num_rows * 5))
plt.suptitle('Distribusi Data Setiap Variabel Numerik', fontsize=16, y=1.02)

for i, col in enumerate(numerical_cols):
    plt.subplot(num_rows, 3, i + 1)
    sns.histplot(df[col], kde=True)
    plt.title(f'Distribusi {col}')
    plt.xlabel(col)
    plt.ylabel('Frekuensi')

plt.tight_layout(rect=[0, 0.03, 1, 0.98])
plt.show()

# Matriks korelasi antar variabel numerik
numerical_df = df.select_dtypes(include=['number'])
correlation_matrix = numerical_df.corr()

print("Matriks Korelasi:")
print(correlation_matrix)

plt.figure(figsize=(12, 10))
sns.heatmap(correlation_matrix, annot=True, cmap='coolwarm', fmt=".2f", linewidths=.5)
plt.title('Matriks Korelasi Antar Variabel Numerik')
plt.show()


# Penanganan Skewness
import numpy as np
from scipy.stats import skew
from sklearn.preprocessing import PowerTransformer

# Cek skewness sebelum transformasi
skew_awal = pd.DataFrame({
    'Variabel': numerical_df.columns,
    'Skewness_Awal': [skew(numerical_df[col].dropna()) for col in numerical_df.columns]
})

print("===== SKEWNESS SEBELUM TRANSFORMASI =====")
print(skew_awal.sort_values(by='Skewness_Awal', ascending=False))

# Pilih variabel dengan |skewness| > 1
threshold = 1
skewed_cols = skew_awal[abs(skew_awal['Skewness_Awal']) > threshold]['Variabel'].tolist()

print("\n===== VARIABEL YANG DITRANSFORM =====")
print(skewed_cols)

df_yj = numerical_df.copy()

# Transformasi Yeo-Johnson
pt = PowerTransformer(method='yeo-johnson', standardize=False)
df_yj[skewed_cols] = pt.fit_transform(numerical_df[skewed_cols])

# Cek skewness setelah transformasi
skew_akhir = pd.DataFrame({
    'Variabel': df_yj.columns,
    'Skewness_Setelah': [skew(df_yj[col].dropna()) for col in df_yj.columns]
})

print("\n===== SKEWNESS SETELAH TRANSFORMASI =====")
print(skew_akhir.sort_values(by='Skewness_Setelah', ascending=False))

# Perbandingan skewness
skew_compare = skew_awal.merge(skew_akhir, on='Variabel')
skew_compare['Ditranformasi'] = skew_compare['Variabel'].isin(skewed_cols)

print("\n===== PERBANDINGAN SKEWNESS =====")
print(skew_compare)

# Visualisasi distribusi sebelum dan sesudah Yeo-Johnson
for col in numerical_df.columns:
    fig, axes = plt.subplots(1, 2, figsize=(12, 4))
    sns.histplot(numerical_df[col], kde=True, ax=axes[0])
    axes[0].set_title(f'Sebelum YJ - {col}')
    sns.histplot(df_yj[col], kde=True, ax=axes[1])
    axes[1].set_title(f'Sesudah YJ - {col}')
    plt.tight_layout()
    plt.show()

df_yj.head()

# Cek korelasi setelah transformasi Yeo-Johnson
corr_matrix = df_yj.corr(method='pearson')

print("\n===== MATRIX KORELASI =====")
print(corr_matrix)

plt.figure(figsize=(10, 8))
sns.heatmap(corr_matrix, annot=True, cmap='coolwarm', fmt='.2f', linewidths=0.5)
plt.title('Heatmap Korelasi Setelah Yeo-Johnson')
plt.tight_layout()
plt.show()


# Min-Max Scaling + Pembalikan Cost Variable
from sklearn.preprocessing import MinMaxScaler

scaler = MinMaxScaler()
df_scaled = pd.DataFrame(scaler.fit_transform(df_yj), columns=df_yj.columns, index=df_yj.index)

# Balik nilai cost variable (semakin kecil semakin baik)
cost_variables = ['DTW_STASIUN']
for col in cost_variables:
    df_scaled[col] = 1 - df_scaled[col]

print(df_scaled.head())


# Entropy Method
epsilon = 1e-12

# Membentuk matriks proporsi
P = df_scaled / (df_scaled.sum(axis=0) + epsilon)
print("===== MATRIKS PROPORSI =====")
print(P.head())

# Hitung nilai entropy
n = len(df_scaled)
k = 1 / np.log(n)
entropy = -k * np.sum(P * np.log(P + epsilon), axis=0)
entropy = pd.Series(entropy, index=df_scaled.columns)

print("\n===== NILAI ENTROPY =====")
print(entropy)

# Degree of diversification
d = 1 - entropy
print("\n===== DEGREE OF DIVERSIFICATION =====")
print(d)

# Hitung bobot entropy
weights = d / d.sum()
weights_df = pd.DataFrame({'Variabel': weights.index, 'Bobot_Entropy': weights.values})
weights_df = weights_df.sort_values(by='Bobot_Entropy', ascending=False)

print("\n===== BOBOT ENTROPY =====")
print(weights_df)


# Hitung Hasil Indeks Komposit
df_scaled['Indeks_Entropy'] = np.dot(df_scaled, weights)
print("===== INDEKS ENTROPY =====")
print(df_scaled['Indeks_Entropy'].head())

# Rescale ke rentang 0-100
df_scaled['Indeks_Entropy_100'] = df_scaled['Indeks_Entropy'] * 100
print("\n===== INDEKS 0-100 =====")
print(df_scaled['Indeks_Entropy_100'].head())


# Evaluasi Robustness (LOVO)
from scipy.stats import pearsonr

base_index = df_scaled['Indeks_Entropy_100']
robustness_results = []
variables = df_scaled.columns[:-2]

for var in variables:
    temp_df = df_scaled[variables].drop(columns=[var])
    P_temp = temp_df / (temp_df.sum(axis=0) + epsilon)
    
    n_temp = len(temp_df)
    k_temp = 1 / np.log(n_temp)
    entropy_temp = -k_temp * np.sum(P_temp * np.log(P_temp + epsilon), axis=0)
    d_temp = 1 - entropy_temp
    weights_temp = d_temp / d_temp.sum()
    
    index_temp = np.dot(temp_df, weights_temp)
    index_temp_100 = ((index_temp - index_temp.min()) / (index_temp.max() - index_temp.min())) * 100
    
    corr, _ = pearsonr(base_index, index_temp_100)
    robustness_results.append({'Dropped_Variable': var, 'Correlation_With_Original': corr})

robustness_df = pd.DataFrame(robustness_results).sort_values(by='Correlation_With_Original', ascending=True)

print("\n===== HASIL ROBUSTNESS LOVO =====")
print(robustness_df)


# Evaluasi Signifikansi Kontribusi Variabel
import statsmodels.api as sm

Y = df_scaled['Indeks_Entropy_100']
X = df_scaled.drop(columns=['Indeks_Entropy', 'Indeks_Entropy_100'])
X = sm.add_constant(X)

model = sm.OLS(Y, X)
results = model.fit()

print("===== REGRESSION RESULTS (P-VALUE SIGNIFICANCE) =====")
print(results.summary())


# Stabilitas Ranking dengan Data IDM
idm_file_path = '/content/drive/MyDrive/Skripsi/05. PENGOLAHAN/IDM.xlsx'
df_idm = pd.read_excel(idm_file_path)

df_idm.head()
print(f"Jumlah baris pada df_idm: {df_idm.shape[0]}")

from scipy.stats import spearmanr

df_indeks = pd.DataFrame({
    'KECAMATAN': df['KECAMATAN'],
    'DESA': df['DESA'],
    'Indeks_Entropy_100': df_scaled['Indeks_Entropy_100']
})

for dframe in [df_indeks, df_idm]:
    dframe['KECAMATAN'] = dframe['KECAMATAN'].astype(str).str.upper().str.strip()
    dframe['DESA'] = dframe['DESA'].astype(str).str.upper().str.strip()

df_eval = pd.merge(df_indeks, df_idm[['KECAMATAN', 'DESA', 'IDM']], on=['KECAMATAN', 'DESA'], how='inner')
print("Jumlah data setelah matching:", len(df_eval))

df_eval['Rank_Entropy'] = df_eval['Indeks_Entropy_100'].rank(ascending=False, method='average')
df_eval['Rank_IDM'] = df_eval['IDM'].rank(ascending=False, method='average')

pearson_corr, pearson_p = pearsonr(df_eval['Indeks_Entropy_100'], df_eval['IDM'])
print("\n===== PEARSON CORRELATION =====")
print("Correlation :", round(pearson_corr, 4))
print("P-value     :", pearson_p)

spearman_corr, spearman_p = spearmanr(df_eval['Rank_Entropy'], df_eval['Rank_IDM'])
print("\n===== SPEARMAN RANK CORRELATION =====")
print("Correlation :", round(spearman_corr, 4))
print("P-value     :", spearman_p)

print("\n===== DATA EVALUASI =====")
print(df_eval.head())

df_eval['Rank_Diff_IDM_Entropy'] = abs(df_eval['Rank_IDM'] - df_eval['Rank_Entropy'])
mean_abs_rank_diff_idm = df_eval['Rank_Diff_IDM_Entropy'].mean()

print(f"\n===== MEAN ABSOLUTE DIFFERENCE OF RANKING (IDM vs Entropy) ====")
print(f"Mean Absolute Difference (Rank_IDM vs Rank_Entropy): {mean_abs_rank_diff_idm:.2f}")

# Scatterplot Indeks Entropy vs IDM
plt.figure(figsize=(8, 6))
sns.regplot(data=df_eval, x='IDM', y='Indeks_Entropy_100', scatter_kws={'s': 50}, line_kws={'linewidth': 2})
plt.title('Scatterplot IDM vs Indeks Entropy')
plt.xlabel('IDM')
plt.ylabel('Indeks Entropy (0-100)')
plt.grid(True)
plt.show()

# Scatterplot Ranking IDM vs Ranking Indeks Entropy
plt.figure(figsize=(8, 6))
sns.regplot(data=df_eval, x='Rank_IDM', y='Rank_Entropy', scatter_kws={'s': 50}, line_kws={'linewidth': 2})
plt.title('Scatterplot Ranking IDM vs Ranking Indeks Entropy')
plt.xlabel('Ranking IDM')
plt.ylabel('Ranking Indeks Entropy')
plt.grid(True)
plt.show()


# Sensitivity Ranking Analysis
base_rank = df_scaled['Indeks_Entropy_100'].rank(ascending=False, method='average')
ranking_results = []

for var in variables:
    temp_df = df_scaled[variables].drop(columns=[var])
    P_temp = temp_df / (temp_df.sum(axis=0) + epsilon)
    
    n_temp = len(temp_df)
    k_temp = 1 / np.log(n_temp)
    entropy_temp = -k_temp * np.sum(P_temp * np.log(P_temp + epsilon), axis=0)
    d_temp = 1 - entropy_temp
    weights_temp = d_temp / d_temp.sum()
    
    index_temp = np.dot(temp_df, weights_temp)
    index_temp_100 = ((index_temp - index_temp.min()) / (index_temp.max() - index_temp.min())) * 100
    rank_temp = pd.Series(index_temp_100).rank(ascending=False, method='average')
    
    rank_diff = abs(base_rank - rank_temp)
    spearman_corr, _ = spearmanr(base_rank, rank_temp)
    
    ranking_results.append({
        'Dropped_Variable': var,
        'Spearman_Rank_Correlation': spearman_corr,
        'Mean_Rank_Change': rank_diff.mean(),
        'Max_Rank_Change': rank_diff.max(),
        'Num_Changed_>10': (rank_diff > 10).sum()
    })

ranking_df = pd.DataFrame(ranking_results).sort_values(by='Spearman_Rank_Correlation')

print("\n===== SENSITIVITY RANKING =====")
print(ranking_df)


# Kontribusi Variabel Aktual ke Indeks
contribution_df = df_scaled[weights.index].multiply(weights, axis=1)
mean_contribution = contribution_df.mean()

mean_contribution_df = pd.DataFrame({'Variabel': mean_contribution.index, 'Mean_Contribution': mean_contribution.values})
mean_contribution_df = mean_contribution_df.sort_values(by='Mean_Contribution', ascending=False)

print("\n===== RATA-RATA KONTRIBUSI =====")
print(mean_contribution_df)

# Visualisasi kontribusi aktual
plt.figure(figsize=(10, 5))
sns.barplot(data=mean_contribution_df, x='Variabel', y='Mean_Contribution')
plt.xticks(rotation=45)
plt.title('Kontribusi Aktual Variabel terhadap Indeks')
plt.xlabel('Variabel')
plt.ylabel('Mean Contribution')
plt.grid(True)
plt.show()

# Distribusi Indeks
plt.figure(figsize=(8, 5))
sns.histplot(df_scaled['Indeks_Entropy_100'], kde=True)
plt.title('Distribusi Indeks Entropy')
plt.xlabel('Indeks Entropy (0-100)')
plt.show()

plt.figure(figsize=(8, 2))
sns.boxplot(x=df_scaled['Indeks_Entropy_100'])
plt.title('Boxplot Indeks Entropy')
plt.show()

print(skew(df_scaled['Indeks_Entropy_100']))


# Evaluasi Menggunakan Data Assesment Wisata
skor_file_path = '/content/drive/MyDrive/Skripsi/05. PENGOLAHAN/SKOR_WISATA.xlsx'
df_skor = pd.read_excel(skor_file_path)

df_skor.head()
print(f"Jumlah baris pada assesment wisata: {df_skor.shape[0]}")

for dframe in [df_indeks, df_skor]:
    dframe['KECAMATAN'] = dframe['KECAMATAN'].astype(str).str.upper().str.strip()
    dframe['DESA'] = dframe['DESA'].astype(str).str.upper().str.strip()

df_eval = pd.merge(df_indeks, df_skor[['KECAMATAN', 'DESA', 'SKOR']], on=['KECAMATAN', 'DESA'], how='inner')
print("Jumlah data setelah matching:", len(df_eval))

df_eval['Rank_Entropy'] = df_eval['Indeks_Entropy_100'].rank(ascending=False, method='average')
df_eval['Rank_SKOR'] = df_eval['SKOR'].rank(ascending=False, method='average')

pearson_corr, pearson_p = pearsonr(df_eval['Indeks_Entropy_100'], df_eval['SKOR'])
print("\n===== PEARSON CORRELATION =====")
print("Correlation :", round(pearson_corr, 4))
print("P-value     :", pearson_p)

spearman_corr, spearman_p = spearmanr(df_eval['Rank_Entropy'], df_eval['Rank_SKOR'])
print("\n===== SPEARMAN RANK CORRELATION =====")
print("Correlation :", round(spearman_corr, 4))
print("P-value     :", spearman_p)

df_eval['Rank_Diff'] = abs(df_eval['Rank_Entropy'] - df_eval['Rank_SKOR'])
mean_abs_rank_diff = df_eval['Rank_Diff'].mean()

print(f"\n===== MEAN ABSOLUTE DIFFERENCE OF RANKING ====")
print(f"Mean Absolute Difference (Rank_Entropy vs Rank_SKOR): {mean_abs_rank_diff:.2f}")

print("\n===== DATA EVALUASI =====")
print(df_eval.head())

# Scatterplot Indeks Entropy vs Assesment Wisata
plt.figure(figsize=(8, 6))
sns.regplot(data=df_eval, x='SKOR', y='Indeks_Entropy_100', scatter_kws={'s': 50}, line_kws={'linewidth': 2})
plt.title('Scatterplot Assesment vs Indeks Entropy')
plt.xlabel('SKOR')
plt.ylabel('Indeks Entropy (0-100)')
plt.grid(True)
plt.show()

# Scatterplot Ranking Assesment vs Ranking Indeks Entropy
plt.figure(figsize=(8, 6))
sns.regplot(data=df_eval, x='Rank_SKOR', y='Rank_Entropy', scatter_kws={'s': 50}, line_kws={'linewidth': 2})
plt.title('Scatterplot Ranking Assesment vs Ranking Indeks Entropy')
plt.xlabel('Ranking SKOR')
plt.ylabel('Ranking Indeks Entropy')
plt.grid(True)
plt.show()


# Merging Final DataFrame
final_df = df[['KECAMATAN', 'DESA']].copy()
final_df['Indeks_Entropy'] = df_scaled['Indeks_Entropy']
final_df['Indeks_Entropy_100'] = df_scaled['Indeks_Entropy_100']

df_idm_cleaned = df_idm.copy()
df_idm_cleaned['KECAMATAN'] = df_idm_cleaned['KECAMATAN'].astype(str).str.upper().str.strip()
df_idm_cleaned['DESA'] = df_idm_cleaned['DESA'].astype(str).str.upper().str.strip()
final_df = pd.merge(final_df, df_idm_cleaned[['KECAMATAN', 'DESA', 'IDM']], on=['KECAMATAN', 'DESA'], how='left')

df_skor_cleaned = df_skor.copy()
df_skor_cleaned['KECAMATAN'] = df_skor_cleaned['KECAMATAN'].astype(str).str.upper().str.strip()
df_skor_cleaned['DESA'] = df_skor_cleaned['DESA'].astype(str).str.upper().str.strip()
final_df = pd.merge(final_df, df_skor_cleaned[['KECAMATAN', 'DESA', 'SKOR']], on=['KECAMATAN', 'DESA'], how='left')

final_df['Ranking indeks entropy'] = final_df['Indeks_Entropy_100'].rank(ascending=False, method='average')
final_df['Ranking IDM'] = final_df['IDM'].rank(ascending=False, method='average')
final_df['Ranking SKOR Assesment'] = final_df['SKOR'].rank(ascending=False, method='average')

final_df = final_df.rename(columns={
    'KECAMATAN': 'kecamatan',
    'DESA': 'desa',
    'Indeks_Entropy_100': 'indeks entropy 0_100',
    'SKOR': 'SKOR assesment'
})

print("DataFrame hasil akhir:")
print(final_df.head())


# Validasi Eksternal Indeks
df_indeks_cleaned = df_indeks.copy()
df_indeks_cleaned['KECAMATAN'] = df_indeks_cleaned['KECAMATAN'].astype(str).str.upper().str.strip()
df_indeks_cleaned['DESA'] = df_indeks_cleaned['DESA'].astype(str).str.upper().str.strip()

df_idm_cleaned_for_eval = df_idm.copy()
df_idm_cleaned_for_eval['KECAMATAN'] = df_idm_cleaned_for_eval['KECAMATAN'].astype(str).str.upper().str.strip()
df_idm_cleaned_for_eval['DESA'] = df_idm_cleaned_for_eval['DESA'].astype(str).str.upper().str.strip()

df_eval_idm_analysis = pd.merge(df_indeks_cleaned, df_idm_cleaned_for_eval[['KECAMATAN', 'DESA', 'IDM']], on=['KECAMATAN', 'DESA'], how='inner')

Y = df_eval_idm_analysis['IDM']
X = sm.add_constant(df_eval_idm_analysis['Indeks_Entropy_100'])

model = sm.OLS(Y, X)
results = model.fit()
print(results.summary())

# Validasi Eksternal OLS (SKOR)
Y = df_eval['SKOR']
X = sm.add_constant(df_eval['Indeks_Entropy_100'])

model = sm.OLS(Y, X)
results = model.fit()
print(results.summary())

# Uji Signifikansi Korelasi Indeks Entropy vs IDM
print("### Uji Signifikansi Korelasi Indeks Entropy vs IDM ###")
pearson_corr_idm, pearson_p_idm = pearsonr(df_eval_idm_analysis['Indeks_Entropy_100'], df_eval_idm_analysis['IDM'])
print("\n===== PEARSON CORRELATION (Indeks Entropy vs IDM) =====")
print(f"Correlation : {pearson_corr_idm:.4f}")
print(f"P-value     : {pearson_p_idm}")
print(f"Signifikan pada alpha 0.05: {'Ya' if pearson_p_idm < 0.05 else 'Tidak'}")

spearman_corr_idm, spearman_p_idm = spearmanr(
    df_eval_idm_analysis['Indeks_Entropy_100'].rank(ascending=False, method='average'),
    df_eval_idm_analysis['IDM'].rank(ascending=False, method='average')
)
print("\n===== SPEARMAN RANK CORRELATION (Indeks Entropy vs IDM) =====")
print(f"Correlation : {spearman_corr_idm:.4f}")
print(f"P-value     : {spearman_p_idm}")
print(f"Signifikan pada alpha 0.05: {'Ya' if spearman_p_idm < 0.05 else 'Tidak'}")

# Uji Signifikansi Korelasi Indeks Entropy vs SKOR Assesment Wisata
print("\n\n### Uji Signifikansi Korelasi Indeks Entropy vs SKOR Assesment Wisata ###")
df_skor_cleaned_for_eval = df_skor.copy()
df_skor_cleaned_for_eval['KECAMATAN'] = df_skor_cleaned_for_eval['KECAMATAN'].astype(str).str.upper().str.strip()
df_skor_cleaned_for_eval['DESA'] = df_skor_cleaned_for_eval['DESA'].astype(str).str.upper().str.strip()

df_eval_skor_analysis = pd.merge(df_indeks_cleaned, df_skor_cleaned_for_eval[['KECAMATAN', 'DESA', 'SKOR']], on=['KECAMATAN', 'DESA'], how='inner')

pearson_corr_skor, pearson_p_skor = pearsonr(df_eval_skor_analysis['Indeks_Entropy_100'], df_eval_skor_analysis['SKOR'])
print("\n===== PEARSON CORRELATION (Indeks Entropy vs SKOR) =====")
print(f"Correlation : {pearson_corr_skor:.4f}")
print(f"P-value     : {pearson_p_skor}")
print(f"Signifikan pada alpha 0.05: {'Ya' if pearson_p_skor < 0.05 else 'Tidak'}")

spearman_corr_skor, spearman_p_skor = spearmanr(
    df_eval_skor_analysis['Indeks_Entropy_100'].rank(ascending=False, method='average'),
    df_eval_skor_analysis['SKOR'].rank(ascending=False, method='average')
)
print("\n===== SPEARMAN RANK CORRELATION (Indeks Entropy vs SKOR) =====")
print(f"Correlation : {spearman_corr_skor:.4f}")
print(f"P-value     : {spearman_p_skor}")
print(f"Signifikan pada alpha 0.05: {'Ya' if spearman_p_skor < 0.05 else 'Tidak'}")

# Ekspor hasil akhir
output_file_path = '/content/drive/MyDrive/Skripsi/05. PENGOLAHAN/Output_Final_Skenario_9.xlsx'
final_df.to_excel(output_file_path, index=False)

print(f"DataFrame 'final_df' berhasil diekspor ke: {output_file_path}")