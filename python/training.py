"""
Training Script - Menjalankan proses training Algoritma C4.5
- Membaca data dari file JSON hasil preprocessing (prioritas)
- Fallback ke database jika file JSON belum ada
- Membangun pohon keputusan
- Menyimpan model (tree + rules) ke file JSON
- Mengembalikan langkah-langkah perhitungan untuk ditampilkan di web
"""

import json
import sys
import os

# Tambah path agar bisa import modul lokal
sys.path.insert(0, os.path.dirname(__file__))

from preprocessing import SUBJECTS
from c45_algorithm import C45Algorithm


def get_processed_data():
    """
    Ambil data yang sudah di-preprocessing.
    Prioritas: baca dari file JSON (lebih cepat, tanpa koneksi DB).
    Fallback: konek ke database langsung.
    """
    storage_dir = os.path.join(os.path.dirname(__file__), '..', 'storage', 'app')
    json_path = os.path.join(storage_dir, 'c45_preprocessed.json')

    # Prioritas 1: Baca dari file JSON yang sudah ada
    if os.path.exists(json_path):
        with open(json_path, 'r', encoding='utf-8') as f:
            data = json.load(f)
        if data.get('success') and data.get('data'):
            return data['data']

    # Fallback: Konek ke database langsung
    from preprocessing import get_data, preprocess
    rows = get_data()
    if not rows:
        return None
    return preprocess(rows)


def train():
    """Jalankan proses training C4.5"""
    processed = get_processed_data()
    if not processed:
        return {'success': False, 'message': 'Tidak ada data training. Jalankan preprocessing terlebih dahulu.'}

    # Siapkan data untuk C4.5 (hanya atribut relevan + status)
    # Identitas seperti id, nama_siswa, nisn akan dibuang (di-ignore)
    training_data = []
    
    # Atribut yang akan digunakan sebagai fitur (rank_paralel dihapus)
    attributes = list(SUBJECTS) + ['pil1_keketatan', 'pil2_keketatan', 'prestasi_tertinggi']
    
    for item in processed:
        row = {subj: item[subj] for subj in SUBJECTS}
        # row['rank_paralel'] = item['rank_paralel']  # Kita abaikan rank paralel agar tidak dominan
        row['pil1_keketatan'] = item['pil1_keketatan']
        row['pil2_keketatan'] = item['pil2_keketatan']
        row['prestasi_tertinggi'] = item['prestasi_tertinggi']
        row['status'] = item['status']
        training_data.append(row)

    # Menangani Imbalanced Data (17 Lolos vs 98 Tidak Lolos) dengan Oversampling
    # Menggandakan data LOLOS secara acak agar jumlahnya seimbang dengan TIDAK LOLOS
    import random
    lolos_data = [d for d in training_data if d['status'] == 'LOLOS']
    tidak_lolos_data = [d for d in training_data if d['status'] == 'TIDAK LOLOS']
    if len(lolos_data) > 0 and len(tidak_lolos_data) > 0:
        if len(lolos_data) < len(tidak_lolos_data):
            diff = len(tidak_lolos_data) - len(lolos_data)
            original_lolos = list(lolos_data)
            for i in range(diff):
                lolos_data.append(original_lolos[i % len(original_lolos)])
        elif len(tidak_lolos_data) < len(lolos_data):
            diff = len(lolos_data) - len(tidak_lolos_data)
            original_tidak_lolos = list(tidak_lolos_data)
            for i in range(diff):
                tidak_lolos_data.append(original_tidak_lolos[i % len(original_tidak_lolos)])
                
        training_data = lolos_data + tidak_lolos_data
        # random.shuffle(training_data) # Dihapus agar urutan tetap sama dan hasil hitungan manual bisa persis sama


    # Bangun pohon keputusan
    algo = C45Algorithm()
    tree = algo.build_tree(training_data, attributes)
    algo.tree = tree

    # Generate rules dari pohon
    rules = algo.tree_to_rules()

    # Simpan dataset oversampling ke CSV untuk keperluan hitung manual skripsi
    storage_dir = os.path.join(os.path.dirname(__file__), '..', 'storage', 'app')
    os.makedirs(storage_dir, exist_ok=True)
    
    try:
        import csv
        csv_path = os.path.join(storage_dir, 'oversampled_dataset.csv')
        if training_data:
            keys = training_data[0].keys()
            with open(csv_path, 'w', newline='', encoding='utf-8') as output_file:
                dict_writer = csv.DictWriter(output_file, keys)
                dict_writer.writeheader()
                dict_writer.writerows(training_data)
    except Exception as e:
        pass

    model_path = os.path.join(storage_dir, 'c45_model.json')
    with open(model_path, 'w', encoding='utf-8') as f:
        json.dump({
            'tree': tree,
            'rules': rules,
            'attributes': attributes,
            'total_training_data': len(training_data)
        }, f, indent=2, ensure_ascii=False)

    steps_path = os.path.join(storage_dir, 'c45_steps.json')
    with open(steps_path, 'w', encoding='utf-8') as f:
        json.dump(algo.steps, f, indent=2, ensure_ascii=False)

    return {
        'success': True,
        'tree': tree,
        'rules': rules,
        'steps': algo.steps,
        'total_data': len(training_data),
        'total_rules': len(rules)
    }


if __name__ == '__main__':
    try:
        result = train()
        print(json.dumps(result, default=str, ensure_ascii=False))
    except Exception as e:
        print(json.dumps({'success': False, 'message': str(e)}))
