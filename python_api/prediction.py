"""
Prediction Script - Prediksi data siswa baru menggunakan model C4.5
- Menerima data nilai siswa via argument JSON
- Load model yang sudah di-training
- Mengembalikan hasil prediksi (LOLOS / TIDAK LOLOS)
"""

import json
import sys
import os

sys.path.insert(0, os.path.dirname(__file__))

from preprocessing import categorize, SUBJECTS
from c45_algorithm import C45Algorithm


def predict(input_data):
    """Prediksi satu sample data siswa"""
    model_path = os.path.join(os.path.dirname(__file__), '..', 'storage', 'app', 'c45_model.json')

    if not os.path.exists(model_path):
        return {'success': False, 'message': 'Model belum di-training. Silakan jalankan proses C4.5 terlebih dahulu.'}

    with open(model_path, 'r', encoding='utf-8') as f:
        model = json.load(f)

    algo = C45Algorithm()
    algo.tree = model['tree']

    # Kategorisasi input nilai
    sample = {}
    for subj in SUBJECTS:
        val = input_data.get(subj, 0)
        sample[subj] = categorize(float(val))

    result = algo.predict(sample)

    return {
        'success': True,
        'prediction': result,
        'input_categorized': sample
    }


if __name__ == '__main__':
    try:
        if len(sys.argv) > 1:
            input_data = json.loads(sys.argv[1])
        else:
            input_data = {}

        result = predict(input_data)
        print(json.dumps(result, default=str, ensure_ascii=False))
    except Exception as e:
        print(json.dumps({'success': False, 'message': str(e)}))
