"""
Testing Script - Pengujian Akurasi Model C4.5
- Split data menjadi training set dan testing set
- Training pada training set
- Testing pada testing set
- Menghitung Confusion Matrix + Akurasi, Presisi, Recall, F1-Score
"""

import json
import sys
import os
import random

sys.path.insert(0, os.path.dirname(__file__))

from preprocessing import SUBJECTS
from c45_algorithm import C45Algorithm


def get_processed_data():
    """
    Ambil data yang sudah di-preprocessing.
    Prioritas: baca dari file JSON (tanpa koneksi DB).
    Fallback: konek ke database langsung.
    """
    storage_dir = os.path.join(os.path.dirname(__file__), '..', 'storage', 'app')
    json_path = os.path.join(storage_dir, 'c45_preprocessed.json')

    if os.path.exists(json_path):
        with open(json_path, 'r', encoding='utf-8') as f:
            data = json.load(f)
        if data.get('success') and data.get('data'):
            return data['data']

    from preprocessing import get_data, preprocess
    rows = get_data()
    if not rows:
        return None
    return preprocess(rows)


def test(method='random', arg1=80, arg2=None):
    """
    Jalankan pengujian akurasi dengan split data
    method: 'random' atau 'year'
    jika 'random', arg1 = split_ratio
    jika 'year', arg1 = list_train_years (misal [2024]), arg2 = list_test_years (misal [2025])
    """
    processed = get_processed_data()
    if not processed:
        return {'success': False, 'message': 'Tidak ada data. Jalankan preprocessing terlebih dahulu.'}

    # Siapkan data
    data = []
    
    # Atribut yang akan digunakan sebagai fitur (rank_paralel dihapus)
    attributes = list(SUBJECTS) + ['pil1_keketatan', 'pil2_keketatan', 'prestasi_tertinggi']
    
    for item in processed:
        row = {subj: item[subj] for subj in SUBJECTS}
        row['pil1_keketatan'] = item['pil1_keketatan']
        row['pil2_keketatan'] = item['pil2_keketatan']
        row['prestasi_tertinggi'] = item['prestasi_tertinggi']
        row['status'] = item['status']
        row['nama_siswa'] = item.get('nama_siswa', '-')
        row['tahun_lulus'] = item.get('tahun_lulus', '-')
        data.append(row)

    if len(data) < 5:
        return {'success': False, 'message': 'Data terlalu sedikit untuk pengujian (minimal 5).'}

    train_data = []
    test_data = []

    if method == 'random':
        split_ratio = int(arg1)
        # Acak dan split data
        random.shuffle(data)
        split_idx = max(1, int(len(data) * split_ratio / 100))
        # Pastikan test set tidak kosong
        if split_idx >= len(data):
            split_idx = len(data) - 1

        train_data = data[:split_idx]
        test_data = data[split_idx:]
    elif method == 'year':
        train_years = arg1 if isinstance(arg1, list) else []
        test_years = arg2 if isinstance(arg2, list) else []
        
        train_years_str = [str(y) for y in train_years]
        test_years_str = [str(y) for y in test_years]

        for d in data:
            if str(d.get('tahun_lulus')) in train_years_str:
                train_data.append(d)
            elif str(d.get('tahun_lulus')) in test_years_str:
                test_data.append(d)
        
        split_ratio = "Tahun Angkatan"

    if not train_data or not test_data:
        return {'success': False, 'message': 'Data tidak cukup untuk split. Pastikan tahun yang dipilih memiliki data yang valid.'}

    # Menangani Imbalanced Data HANYA pada Data Training
    # Agar pohon yang dibentuk seimbang pemahamannya antara LOLOS dan TIDAK LOLOS
    lolos_data = [d for d in train_data if d['status'] == 'LOLOS']
    tidak_lolos_data = [d for d in train_data if d['status'] == 'TIDAK LOLOS']
    
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
                
        train_data = lolos_data + tidak_lolos_data
        # random.shuffle(train_data) # Dihapus agar urutan tetap sama dan hasil hitungan manual bisa persis sama

    # Training
    algo = C45Algorithm()
    tree = algo.build_tree(train_data, attributes)
    algo.tree = tree

    # Testing
    tp = tn = fp = fn = 0
    details = []

    for sample in test_data:
        actual = sample['status']
        predicted = algo.predict(sample)

        if actual == 'LOLOS' and predicted == 'LOLOS':
            tp += 1
        elif actual == 'TIDAK LOLOS' and predicted == 'TIDAK LOLOS':
            tn += 1
        elif actual == 'TIDAK LOLOS' and predicted == 'LOLOS':
            fp += 1
        elif actual == 'LOLOS' and predicted == 'TIDAK LOLOS':
            fn += 1

        details.append({
            'nama': sample.get('nama_siswa', '-'),
            'tahun': sample.get('tahun_lulus', '-'),
            'actual': actual,
            'predicted': predicted,
            'correct': actual == predicted
        })

    total_test = tp + tn + fp + fn
    accuracy = ((tp + tn) / total_test * 100) if total_test > 0 else 0
    precision = (tp / (tp + fp) * 100) if (tp + fp) > 0 else 0
    recall = (tp / (tp + fn) * 100) if (tp + fn) > 0 else 0
    f1 = (2 * precision * recall / (precision + recall)) if (precision + recall) > 0 else 0

    return {
        'success': True,
        'method': method,
        'split_ratio': split_ratio,
        'train_count': len(train_data),
        'test_count': len(test_data),
        'confusion_matrix': {
            'tp': tp, 'tn': tn, 'fp': fp, 'fn': fn
        },
        'metrics': {
            'accuracy': round(accuracy, 2),
            'precision': round(precision, 2),
            'recall': round(recall, 2),
            'f1_score': round(f1, 2)
        },
        'details': details
    }


if __name__ == '__main__':
    try:
        method = sys.argv[1] if len(sys.argv) > 1 else 'random'
        if method == 'random':
            split_ratio = int(sys.argv[2]) if len(sys.argv) > 2 else 80
            result = test(method, split_ratio)
        elif method == 'year':
            train_str = sys.argv[2] if len(sys.argv) > 2 else ""
            test_str = sys.argv[3] if len(sys.argv) > 3 else ""
            train_years = [y.strip() for y in train_str.split(',') if y.strip()]
            test_years = [y.strip() for y in test_str.split(',') if y.strip()]
            result = test(method, train_years, test_years)
        else:
            result = test()

        # Otomatis simpan ke file JSON di storage Laravel
        if result.get('success', False):
            storage_dir = os.path.join(os.path.dirname(__file__), '..', 'storage', 'app')
            os.makedirs(storage_dir, exist_ok=True)
            save_path = os.path.join(storage_dir, 'c45_test_result.json')
            with open(save_path, 'w', encoding='utf-8') as f:
                json.dump(result, f, indent=2, default=str, ensure_ascii=False)

        print(json.dumps(result, default=str, ensure_ascii=False))
    except Exception as e:
        print(json.dumps({'success': False, 'message': str(e)}))
