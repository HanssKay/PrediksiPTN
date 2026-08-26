"""
Preprocessing Data untuk Algoritma C4.5
- Mengambil data dari database MySQL
- Menghitung rata-rata per mata pelajaran dari 5 semester
- Mengkategorikan nilai menjadi: Sangat Baik, Baik, Cukup, Kurang
"""

import json
import sys
import os

# Daftar 7 mata pelajaran
SUBJECTS = [
    'bind', 'bing', 'mtk_wajib', 'mtk_lanjut', 'fisika', 'kimia', 'biologi'
]

def categorize(value):
    """
    Kategorisasi nilai rata-rata:
    - Sangat Baik: >= 90
    - Baik: 80 - 89.99
    - Cukup: 70 - 79.99
    - Kurang: < 70
    """
    if value is None or value == 0:
        return 'Kurang'
    if value >= 90:
        return 'Sangat Baik'
    elif value >= 80:
        return 'Baik'
    elif value >= 70:
        return 'Cukup'
    else:
        return 'Kurang'

def preprocess(rows):
    """
    Proses data mentah menjadi data terkategorisasi.
    Setiap mapel dihitung rata-ratanya dari 5 semester, lalu dikategorikan.
    """
    processed = []
    for row in rows:
        item = {
            'id': row['id'],
            'nama_siswa': row.get('nama_siswa', '-'),
            'tahun_lulus': row.get('tahun_lulus', '-'),
            'status': row['status']
        }

        # Hitung rata-rata per mata pelajaran dari 5 semester
        for subj in SUBJECTS:
            values = []
            for smt in range(1, 6):
                col = f"{subj}_smt{smt}"
                val = row.get(col)
                if val is not None:
                    try:
                        values.append(float(val))
                    except (ValueError, TypeError):
                        pass

            avg = sum(values) / len(values) if values else 0
            item[f"{subj}_avg"] = round(avg, 2)
            item[subj] = categorize(avg)

        # Tambahkan atribut baru lainnya agar bisa dipakai saat training
        item['rank_paralel'] = row.get('rank_paralel')
        
        # Keketatan bisa berupa angka persentase atau teks (Tinggi, Sedang, Rendah)
        # Kita gunakan .title() dan .strip() agar "tinggi" dan "Tinggi" dianggap sama
        pil1 = str(row.get('pil1_keketatan') or '-')
        pil2 = str(row.get('pil2_keketatan') or '-')
        prestasi = str(row.get('prestasi_tertinggi') or 'Tidak Ada')

        item['pil1_keketatan'] = pil1.strip().title()
        item['pil2_keketatan'] = pil2.strip().title()
        item['prestasi_tertinggi'] = prestasi.strip().title()

        processed.append(item)

    return processed

def get_frequency_table(processed_data):
    """
    Buat tabel frekuensi per atribut per kategori
    """
    freq = {}
    for subj in SUBJECTS:
        freq[subj] = {
            'Sangat Baik': {'total': 0, 'LOLOS': 0, 'TIDAK LOLOS': 0},
            'Baik': {'total': 0, 'LOLOS': 0, 'TIDAK LOLOS': 0},
            'Cukup': {'total': 0, 'LOLOS': 0, 'TIDAK LOLOS': 0},
            'Kurang': {'total': 0, 'LOLOS': 0, 'TIDAK LOLOS': 0}
        }
        for item in processed_data:
            cat = item[subj]
            status = item['status']
            freq[subj][cat]['total'] += 1
            freq[subj][cat][status] += 1

    return freq

def run_preprocessing(rows):
    try:
        processed = preprocess(rows)
        freq = get_frequency_table(processed)

        output = {
            'success': True,
            'data': processed,
            'frequency': freq,
            'total': len(processed),
            'subjects': SUBJECTS
        }
        return output
    except Exception as e:
        return {'success': False, 'message': str(e)}
