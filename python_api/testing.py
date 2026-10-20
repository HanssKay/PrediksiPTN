"""
Testing Script - Pengujian Akurasi Model C4.5
- Split data menjadi training set dan testing set
- Training pada training set
- Testing pada testing set
- Menghitung Confusion Matrix + Akurasi, Presisi, Recall, F1-Score
"""

import json
import os
import random

from preprocessing import SUBJECTS
from c45_algorithm import C45Algorithm

def run_testing(processed_data, method='random', arg1=80, arg2=None):
    """
    Jalankan pengujian akurasi dengan split data
    """
    if not processed_data:
        return {'success': False, 'message': 'Tidak ada data. Jalankan preprocessing terlebih dahulu.'}

    # Siapkan data
    data = []
    
    # Atribut yang akan digunakan sebagai fitur
    attributes = list(SUBJECTS) + ['pil1_keketatan', 'pil2_keketatan', 'prestasi_tertinggi']
    
    for item in processed_data:
        row = {subj: item.get(subj, 'Kurang') for subj in SUBJECTS}
        row['pil1_keketatan'] = item.get('pil1_keketatan', '-')
        row['pil2_keketatan'] = item.get('pil2_keketatan', '-')
        row['prestasi_tertinggi'] = item.get('prestasi_tertinggi', 'Tidak Ada')
        row['status'] = item.get('status', 'TIDAK LOLOS')
        row['nama_siswa'] = item.get('nama_siswa', '-')
        row['tahun_lulus'] = item.get('tahun_lulus', '-')
        data.append(row)

    if len(data) < 5:
        return {'success': False, 'message': 'Data terlalu sedikit untuk pengujian (minimal 5).'}

    train_data = []
    test_data = []
    split_ratio = "Tahun Angkatan"

    if method == 'random':
        try:
            split_ratio = int(arg1)
        except:
            split_ratio = 80
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
        
        train_years_str = [str(y).strip() for y in train_years if str(y).strip()]
        test_years_str = [str(y).strip() for y in test_years if str(y).strip()]

        for d in data:
            if str(d.get('tahun_lulus')) in train_years_str:
                train_data.append(d)
            elif str(d.get('tahun_lulus')) in test_years_str:
                test_data.append(d)

    if not train_data or not test_data:
        return {'success': False, 'message': 'Data tidak cukup untuk split. Pastikan tahun yang dipilih memiliki data yang valid.'}

    # Tanpa Oversampling (Data training dan data testing murni data asli)

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
