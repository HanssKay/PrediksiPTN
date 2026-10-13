from flask import Flask, request, jsonify
from preprocessing import run_preprocessing
from training import run_training
from testing import run_testing

app = Flask(__name__)

@app.route('/api/preprocess', methods=['POST'])
def api_preprocess():
    try:
        data = request.json
        if not data or not isinstance(data, list):
            return jsonify({'success': False, 'message': 'Data harus berupa list (array) dari baris-baris data.'}), 400
        
        result = run_preprocessing(data)
        return jsonify(result)
    except Exception as e:
        return jsonify({'success': False, 'message': str(e)}), 500

@app.route('/api/train', methods=['POST'])
def api_train():
    try:
        data = request.json
        if not data or not isinstance(data, list):
            return jsonify({'success': False, 'message': 'Data preprocessing diperlukan.'}), 400
        
        result = run_training(data)
        return jsonify(result)
    except Exception as e:
        return jsonify({'success': False, 'message': str(e)}), 500

@app.route('/api/test', methods=['POST'])
def api_test():
    try:
        payload = request.json
        if not payload or not isinstance(payload, dict):
            return jsonify({'success': False, 'message': 'Payload tidak valid.'}), 400
        
        data = payload.get('data', [])
        method = payload.get('method', 'random')
        arg1 = payload.get('arg1', 80)
        arg2 = payload.get('arg2')

        if not data:
            return jsonify({'success': False, 'message': 'Data preprocessing diperlukan.'}), 400
        
        result = run_testing(data, method=method, arg1=arg1, arg2=arg2)
        return jsonify(result)
    except Exception as e:
        return jsonify({'success': False, 'message': str(e)}), 500

@app.route('/', methods=['GET'])
def index():
    return jsonify({'success': True, 'message': 'API Prediksi SNBP Aktif!'})

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000, debug=False)
