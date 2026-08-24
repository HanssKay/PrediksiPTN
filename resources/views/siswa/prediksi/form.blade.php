<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Uji Prediksi Kelulusan SNBP') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto space-y-6">

        {{-- Flash Error --}}
        @if(session('error'))
        <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg border border-red-200" role="alert">
            <span class="font-medium">Gagal!</span> {{ session('error') }}
        </div>
        @endif

        {{-- Validation Errors --}}
        @if($errors->any())
        <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg border border-red-200">
            <p class="font-medium mb-1">Terjadi kesalahan:</p>
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('siswa.prediksi.proses') }}" method="POST">
            @csrf

            {{-- SECTION 1: Nilai Rata-rata --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
                <div class="flex items-center mb-5 pb-4 border-b border-gray-100">
                    <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Nilai Rata-rata Rapot</h3>
                        <p class="text-xs text-gray-500">Masukkan rata-rata nilai dari Semester 1 sampai 5</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-x-6 gap-y-4">
                    {{-- B. Indonesia --}}
                    <div>
                        <label for="bind_avg" class="block text-sm font-medium text-gray-700 mb-1">Bahasa Indonesia</label>
                        <input type="number" step="0.01" min="0" max="100" id="bind_avg" name="bind_avg" value="{{ old('bind_avg') }}" 
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-blue-500 focus:border-blue-500 transition" placeholder="Contoh: 85.50" required>
                    </div>
                    {{-- B. Inggris --}}
                    <div>
                        <label for="bing_avg" class="block text-sm font-medium text-gray-700 mb-1">Bahasa Inggris</label>
                        <input type="number" step="0.01" min="0" max="100" id="bing_avg" name="bing_avg" value="{{ old('bing_avg') }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-blue-500 focus:border-blue-500 transition" placeholder="Contoh: 82.00" required>
                    </div>
                    {{-- Mtk Wajib --}}
                    <div>
                        <label for="mtk_wajib_avg" class="block text-sm font-medium text-gray-700 mb-1">Matematika Wajib</label>
                        <input type="number" step="0.01" min="0" max="100" id="mtk_wajib_avg" name="mtk_wajib_avg" value="{{ old('mtk_wajib_avg') }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-blue-500 focus:border-blue-500 transition" placeholder="Contoh: 78.25" required>
                    </div>
                    {{-- Mtk Lanjut --}}
                    <div>
                        <label for="mtk_lanjut_avg" class="block text-sm font-medium text-gray-700 mb-1">Matematika Lanjutan</label>
                        <input type="number" step="0.01" min="0" max="100" id="mtk_lanjut_avg" name="mtk_lanjut_avg" value="{{ old('mtk_lanjut_avg') }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-blue-500 focus:border-blue-500 transition" placeholder="Contoh: 75.00" required>
                    </div>
                    {{-- Fisika --}}
                    <div>
                        <label for="fisika_avg" class="block text-sm font-medium text-gray-700 mb-1">Fisika</label>
                        <input type="number" step="0.01" min="0" max="100" id="fisika_avg" name="fisika_avg" value="{{ old('fisika_avg') }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-blue-500 focus:border-blue-500 transition" placeholder="Contoh: 88.00" required>
                    </div>
                    {{-- Kimia --}}
                    <div>
                        <label for="kimia_avg" class="block text-sm font-medium text-gray-700 mb-1">Kimia</label>
                        <input type="number" step="0.01" min="0" max="100" id="kimia_avg" name="kimia_avg" value="{{ old('kimia_avg') }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-blue-500 focus:border-blue-500 transition" placeholder="Contoh: 80.00" required>
                    </div>
                    {{-- Biologi --}}
                    <div>
                        <label for="biologi_avg" class="block text-sm font-medium text-gray-700 mb-1">Biologi</label>
                        <input type="number" step="0.01" min="0" max="100" id="biologi_avg" name="biologi_avg" value="{{ old('biologi_avg') }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-blue-500 focus:border-blue-500 transition" placeholder="Contoh: 83.50" required>
                    </div>
                </div>
            </div>

            {{-- SECTION 2: Pilihan Universitas --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
                <div class="flex items-center mb-5 pb-4 border-b border-gray-100">
                    <div class="w-10 h-10 bg-cyan-100 rounded-xl flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Pilihan Universitas & Program Studi</h3>
                        <p class="text-xs text-gray-500">Masukkan pilihan SNBP kamu</p>
                    </div>
                </div>

                {{-- Pilihan 1 --}}
                <div class="mb-6">
                    <p class="text-sm font-bold text-blue-600 mb-3 flex items-center">
                        <span class="w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-xs font-bold mr-2">1</span>
                        Pilihan Pertama <span class="text-red-500 ml-1">*</span>
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="univ_pil1_id" class="block text-sm font-medium text-gray-700 mb-1">Universitas</label>
                            <!-- We still need to send univ_pil1 text to the backend, so we use a hidden input for text, or just let the backend handle it? The backend expects univ_pil1 string. We can either change backend or just let select name="univ_pil1" have the string value. Actually, let's use the univ name as the option value. -->
                            <select id="univ_pil1" name="univ_pil1" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-blue-500 focus:border-blue-500 transition" required>
                                <option value="" disabled selected>-- Pilih Universitas --</option>
                                @foreach($universitas as $univ)
                                    <option value="{{ $univ->nama_universitas }}" data-id="{{ $univ->id }}" {{ old('univ_pil1') == $univ->nama_universitas ? 'selected' : '' }}>{{ $univ->nama_universitas }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="prodi_pil1" class="block text-sm font-medium text-gray-700 mb-1">Program Studi</label>
                            <select id="prodi_pil1" name="prodi_pil1" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-blue-500 focus:border-blue-500 transition" required>
                                <option value="" disabled selected>-- Pilih Prodi --</option>
                                <!-- Akan diisi oleh JS -->
                            </select>
                        </div>
                        <div>
                            <label for="keketatan_pil1" class="block text-sm font-medium text-gray-700 mb-1">Keketatan</label>
                            <input type="text" id="keketatan_pil1" name="keketatan_pil1" value="{{ old('keketatan_pil1') }}"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm bg-gray-50 focus:ring-blue-500 focus:border-blue-500 transition" readonly required placeholder="Otomatis terisi">
                        </div>
                    </div>
                </div>

                {{-- Pilihan 2 --}}
                <div>
                    <p class="text-sm font-bold text-cyan-600 mb-3 flex items-center">
                        <span class="w-6 h-6 bg-cyan-600 text-white rounded-full flex items-center justify-center text-xs font-bold mr-2">2</span>
                        Pilihan Kedua <span class="text-gray-400 text-xs ml-2">(Opsional)</span>
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="univ_pil2" class="block text-sm font-medium text-gray-700 mb-1">Universitas</label>
                            <select id="univ_pil2" name="univ_pil2" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-blue-500 focus:border-blue-500 transition">
                                <option value="" selected>-- Tidak Memilih --</option>
                                @foreach($universitas as $univ)
                                    <option value="{{ $univ->nama_universitas }}" data-id="{{ $univ->id }}" {{ old('univ_pil2') == $univ->nama_universitas ? 'selected' : '' }}>{{ $univ->nama_universitas }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="prodi_pil2" class="block text-sm font-medium text-gray-700 mb-1">Program Studi</label>
                            <select id="prodi_pil2" name="prodi_pil2" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-blue-500 focus:border-blue-500 transition">
                                <option value="" selected>-- Tidak Memilih --</option>
                                <!-- Akan diisi oleh JS -->
                            </select>
                        </div>
                        <div>
                            <label for="keketatan_pil2" class="block text-sm font-medium text-gray-700 mb-1">Keketatan</label>
                            <input type="text" id="keketatan_pil2" name="keketatan_pil2" value="{{ old('keketatan_pil2') }}"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm bg-gray-50 focus:ring-blue-500 focus:border-blue-500 transition" readonly placeholder="Otomatis terisi">
                        </div>
                    </div>
                </div>
            </div>

            {{-- SECTION 3: Prestasi --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
                <div class="flex items-center mb-5 pb-4 border-b border-gray-100">
                    <div class="w-10 h-10 bg-teal-100 rounded-xl flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Prestasi</h3>
                        <p class="text-xs text-gray-500">Pilih prestasi tertinggi yang kamu miliki</p>
                    </div>
                </div>

                <div>
                    <label for="prestasi_tertinggi" class="block text-sm font-medium text-gray-700 mb-1">Prestasi Tertinggi</label>
                    <select id="prestasi_tertinggi" name="prestasi_tertinggi" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-blue-500 focus:border-blue-500 transition" required>
                        <option value="" disabled {{ old('prestasi_tertinggi') ? '' : 'selected' }}>-- Pilih Prestasi --</option>
                        <option value="Internasional" {{ old('prestasi_tertinggi') == 'Internasional' ? 'selected' : '' }}>Tingkat Internasional</option>
                        <option value="Nasional" {{ old('prestasi_tertinggi') == 'Nasional' ? 'selected' : '' }}>Tingkat Nasional</option>
                        <option value="Provinsi" {{ old('prestasi_tertinggi') == 'Provinsi' ? 'selected' : '' }}>Tingkat Provinsi</option>
                        <option value="Kabupaten" {{ old('prestasi_tertinggi') == 'Kabupaten' ? 'selected' : '' }}>Tingkat Kabupaten/Kota</option>
                        <option value="Sekolah" {{ old('prestasi_tertinggi') == 'Sekolah' ? 'selected' : '' }}>Tingkat Sekolah</option>
                        <option value="Tidak Ada" {{ old('prestasi_tertinggi') == 'Tidak Ada' ? 'selected' : '' }}>Tidak Ada Prestasi</option>
                    </select>
                </div>
            </div>

            {{-- Submit Button --}}
            <div class="flex justify-end">
                <button type="submit" 
                        class="inline-flex items-center px-8 py-3.5 bg-gradient-to-r from-blue-600 to-cyan-600 text-white font-bold text-sm rounded-xl hover:from-blue-700 hover:to-cyan-700 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 cursor-pointer">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    Prediksi Sekarang
                </button>
            </div>

        </form>

    </div>

    @push('scripts')
    <script>
        const masterData = @json($universitas);
        
        function setupDropdowns(prefix) {
            const univSelect = document.getElementById('univ_' + prefix);
            const prodiSelect = document.getElementById('prodi_' + prefix);
            const keketatanInput = document.getElementById('keketatan_' + prefix);
            
            // Simpan nilai old (jika ada error validasi)
            const oldProdi = "{{ old('prodi_pil1') }}";
            const oldProdi2 = "{{ old('prodi_pil2') }}";
            const targetOldProdi = prefix === 'pil1' ? oldProdi : oldProdi2;
            
            function populateProdi() {
                const selectedOption = univSelect.options[univSelect.selectedIndex];
                const univId = selectedOption ? selectedOption.dataset.id : null;
                
                prodiSelect.innerHTML = '<option value="" '+(prefix==='pil1'?'disabled':'')+' selected>-- Pilih Prodi --</option>';
                if(!targetOldProdi) keketatanInput.value = '';
                
                if (!univId) return;
                
                const univ = masterData.find(u => u.id == univId);
                if (univ && univ.prodis) {
                    univ.prodis.forEach(prodi => {
                        const option = document.createElement('option');
                        option.value = prodi.nama_prodi;
                        option.dataset.keketatan = prodi.keketatan;
                        option.textContent = prodi.nama_prodi;
                        
                        // Set selected jika sama dengan old value
                        if (targetOldProdi === prodi.nama_prodi) {
                            option.selected = true;
                            keketatanInput.value = prodi.keketatan;
                        }
                        
                        prodiSelect.appendChild(option);
                    });
                }
            }
            
            univSelect.addEventListener('change', function() {
                populateProdi();
                keketatanInput.value = ''; // Reset saat univ berubah
            });
            
            prodiSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                if (selectedOption && selectedOption.dataset.keketatan) {
                    keketatanInput.value = selectedOption.dataset.keketatan;
                } else {
                    keketatanInput.value = '';
                }
            });
            
            // Panggil saat pertama kali load (untuk menangani old input)
            populateProdi();
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            setupDropdowns('pil1');
            setupDropdowns('pil2');
        });
    </script>
    @endpush
</x-app-layout>
