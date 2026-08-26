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

from preprocessing import SUBJECTS
from c45_algorithm import C45Algorithm

def run_training(processed_data):
    """Jalankan proses training C4.5"""
    if not processed_data:
        return {'success': False, 'message': 'Tidak ada data training.'}

    # Siapkan data untuk C4.5 (hanya atribut relevan + status)
    training_data = []
    
    # Atribut yang akan digunakan sebagai fitur
    attributes = list(SUBJECTS) + ['pil1_keketatan', 'pil2_keketatan', 'prestasi_tertinggi']
    
    for item in processed_data:
        row = {subj: item.get(subj, 'Kurang') for subj in SUBJECTS}
        row['pil1_keketatan'] = item.get('pil1_keketatan', '-')
        row['pil2_keketatan'] = item.get('pil2_keketatan', '-')
        row['prestasi_tertinggi'] = item.get('prestasi_tertinggi', 'Tidak Ada')
        row['status'] = item.get('status', 'TIDAK LOLOS')
        training_data.append(row)

    # Menangani Imbalanced Data dengan Oversampling
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

    # Bangun pohon keputusan
    algo = C45Algorithm()
    tree = algo.build_tree(training_data, attributes)
    algo.tree = tree

    # Generate rules dari pohon
    rules = algo.tree_to_rules()

    # Generate oversampled CSV content as string (to be saved by Laravel)
    csv_content = ""
    try:
        import io
        import csv
        if training_data:
            output = io.StringIO()
            keys = training_data[0].keys()
            dict_writer = csv.DictWriter(output, keys)
            dict_writer.writeheader()
            dict_writer.writerows(training_data)
            csv_content = output.getvalue()
    except Exception as e:
        pass

    model_data = {
        'tree': tree,
        'rules': rules,
        'attributes': attributes,
        'total_training_data': len(training_data)
    }

    return {
        'success': True,
        'tree': tree,
        'rules': rules,
        'steps': algo.steps,
        'total_data': len(training_data),
        'total_rules': len(rules),
        'model_data': model_data,
        'csv_content': csv_content
    }
