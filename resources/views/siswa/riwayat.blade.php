<x-app-layout>
    <div class="space-y-6 max-w-6xl mx-auto" x-data="{ detailModal: null }">

        {{-- Top Header Banner --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Riwayat Prediksi SNBP</h1>
                <p class="text-xs text-slate-500 mt-1">Daftar seluruh simulasi & pengujian rekomendasi prodi yang pernah kamu lakukan.</p>
            </div>
            <a href="{{ route('siswa.prediksi.form') }}" class="inline-flex items-center px-4 py-2.5 text-xs font-bold rounded-xl bg-indigo-600 text-white hover:bg-indigo-700 shadow-md shadow-indigo-200 transition-all self-start sm:self-auto">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Mulai Prediksi Baru
            </a>
        </div>

        {{-- Main Card Table Container --}}
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200/80 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-indigo-600"></span>
                    <h3 class="text-xs font-black uppercase tracking-wider text-slate-800">Histori Pengujian Siswa</h3>
                </div>
                <span class="text-xs text-slate-400 font-medium">Total: {{ $riwayats->total() }} Sesi</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-100 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                            <th class="py-3.5 px-5 text-center w-12">#</th>
                            <th class="py-3.5 px-5">Waktu Prediksi</th>
                            <th class="py-3.5 px-5">Pilihan 1 (Prioritas)</th>
                            <th class="py-3.5 px-5">Pilihan 2 (Cadangan)</th>
                            <th class="py-3.5 px-5 text-center">Hasil Klasifikasi</th>
                            <th class="py-3.5 px-5 text-center w-36">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs">
                        @forelse($riwayats as $index => $riwayat)
                        @php
                            $hasil = strtoupper(trim($riwayat->hasil_prediksi ?? ''));
                            $isLolos = str_contains($hasil, 'LOLOS') && !str_contains($hasil, 'TIDAK');
                        @endphp
                        <tr class="hover:bg-indigo-50/30 transition-colors group">
                            {{-- Number --}}
                            <td class="py-4 px-5 text-center font-bold text-slate-400">
                                {{ $riwayats->firstItem() + $index }}
                            </td>

                            {{-- Tanggal & Mode --}}
                            <td class="py-4 px-5 whitespace-nowrap">
                                <span class="font-semibold text-slate-800 block">{{ $riwayat->created_at->format('d M Y') }}</span>
                                <div class="flex items-center gap-1.5 mt-1">
                                    <span class="text-[11px] text-slate-400 font-mono">{{ $riwayat->created_at->format('H:i') }} WIB</span>
                                    @if(($riwayat->mode_prediksi ?? 'resmi') === 'resmi')
                                        <span class="px-1.5 py-0.5 rounded text-[10px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            Rapor Resmi
                                        </span>
                                    @else
                                        <span class="px-1.5 py-0.5 rounded text-[10px] font-extrabold bg-amber-50 text-amber-700 border border-amber-200">
                                            Simulasi
                                        </span>
                                    @endif
                                </div>
                            </td>

                            {{-- Pilihan 1 --}}
                            <td class="py-4 px-5">
                                <div class="font-bold text-slate-900 group-hover:text-indigo-600 transition-colors leading-snug">
                                    {{ $riwayat->univ_pil1 }}
                                </div>
                                <div class="text-[11px] text-slate-500 mt-0.5 flex items-center gap-1.5">
                                    <span>{{ $riwayat->prodi_pil1 }}</span>
                                    <span class="px-1.5 py-0.2 rounded text-[10px] font-bold {{ $riwayat->keketatan_pil1 === 'Sangat Ketat' ? 'bg-rose-50 text-rose-600 border border-rose-100' : 'bg-slate-100 text-slate-600' }}">
                                        {{ $riwayat->keketatan_pil1 ?? 'Normal' }}
                                    </span>
                                </div>
                            </td>

                            {{-- Pilihan 2 --}}
                            <td class="py-4 px-5">
                                @if($riwayat->univ_pil2)
                                    <div class="font-bold text-slate-700 leading-snug">{{ $riwayat->univ_pil2 }}</div>
                                    <div class="text-[11px] text-slate-500 mt-0.5 flex items-center gap-1.5">
                                        <span>{{ $riwayat->prodi_pil2 }}</span>
                                        @if($riwayat->keketatan_pil2)
                                        <span class="px-1.5 py-0.2 rounded text-[10px] font-bold bg-slate-100 text-slate-600">
                                            {{ $riwayat->keketatan_pil2 }}
                                        </span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-[11px] text-slate-400 italic">Tidak memilih</span>
                                @endif
                            </td>

                            {{-- Hasil Prediksi --}}
                            <td class="py-4 px-5 text-center">
                                @if($isLolos)
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-[11px] font-black bg-emerald-50 text-emerald-700 border border-emerald-200 shadow-xs">
                                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                        {{ $hasil }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-[11px] font-black bg-rose-50 text-rose-700 border border-rose-200 shadow-xs">
                                        <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        TIDAK LOLOS
                                    </span>
                                @endif
                            </td>

                            {{-- Aksi --}}
                            <td class="py-4 px-5 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    {{-- Halaman Hasil Lengkap --}}
                                    <a href="{{ route('siswa.prediksi.hasil', $riwayat->id) }}" class="p-2 text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-xl transition-all shadow-xs" title="Buka Hasil Lengkap">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                        </svg>
                                    </a>

                                    {{-- Quick Modal Preview --}}
                                    <button type="button" @click="detailModal = {{ $riwayat->id }}" class="p-2 text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-all shadow-xs" title="Lihat Nilai Cepat">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </button>

                                    {{-- Hapus --}}
                                    <form action="{{ route('siswa.prediksi.destroy', $riwayat->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data riwayat ini?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-rose-600 bg-rose-50 hover:bg-rose-100 rounded-xl transition-all shadow-xs" title="Hapus Riwayat">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-16 px-6 text-center">
                                <div class="max-w-sm mx-auto flex flex-col items-center">
                                    <div class="w-16 h-16 rounded-3xl bg-slate-100 flex items-center justify-center text-slate-400 mb-4">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <h4 class="text-base font-bold text-slate-800">Belum Ada Riwayat Prediksi</h4>
                                    <p class="text-xs text-slate-500 mt-1 mb-5">Kamu belum pernah melakukan simulasi SNBP. Coba masukkan nilai rapormu sekarang untuk melihat analisis peluang kelulusan.</p>
                                    <a href="{{ route('siswa.prediksi.form') }}" class="px-5 py-2.5 rounded-xl bg-indigo-600 text-white font-bold text-xs shadow-md shadow-indigo-200 hover:bg-indigo-700 transition-all">
                                        Mulai Uji Prediksi
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination footer --}}
            @if($riwayats->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                {{ $riwayats->links() }}
            </div>
            @endif
        </div>

        {{-- CLEAN FULLSCREEN MODAL DETAIL --}}
        @foreach($riwayats as $riwayat)
        <div x-show="detailModal === {{ $riwayat->id }}" 
             style="display:none;" 
             class="fixed inset-0 z-50 overflow-y-auto"
             role="dialog" 
             aria-modal="true">
            
            {{-- Backdrop --}}
            <div x-show="detailModal === {{ $riwayat->id }}" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0" 
                 x-transition:enter-end="opacity-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100" 
                 x-transition:leave-end="opacity-0" 
                 @click="detailModal = null" 
                 class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm transition-opacity"></div>

            {{-- Modal Dialog --}}
            <div class="flex items-center justify-center min-h-screen px-4 py-8">
                <div x-show="detailModal === {{ $riwayat->id }}" 
                     x-transition:enter="ease-out duration-300" 
                     x-transition:enter-start="opacity-0 scale-95 translate-y-4" 
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0" 
                     x-transition:leave="ease-in duration-200" 
                     x-transition:leave-start="opacity-100 scale-100 translate-y-0" 
                     x-transition:leave-end="opacity-0 scale-95 translate-y-4" 
                     class="relative bg-white rounded-3xl shadow-2xl max-w-2xl w-full p-6 sm:p-8 text-left border border-slate-200 overflow-hidden z-10">
                    
                    {{-- Modal Header --}}
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                        <div>
                            <span class="text-[10px] font-black uppercase tracking-wider text-indigo-600">Simulasi #{{ $riwayat->id }}</span>
                            <h3 class="text-lg font-black text-slate-900 mt-0.5">Ringkasan Data Masukan</h3>
                            <p class="text-xs text-slate-400">{{ $riwayat->created_at->format('d F Y &bull; H:i') }} WIB</p>
                        </div>
                        <button type="button" @click="detailModal = null" class="w-8 h-8 rounded-full bg-slate-100 text-slate-400 hover:text-slate-600 hover:bg-slate-200 flex items-center justify-center transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    {{-- Modal Body --}}
                    <div class="py-5 grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Nilai Raport --}}
                        <div class="space-y-3">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-700 flex items-center gap-1.5">
                                <span>📊</span> Rata-Rata Nilai Rapor
                            </h4>
                            <div class="space-y-2 bg-slate-50 p-3.5 rounded-2xl border border-slate-100 text-xs text-slate-600">
                                <div class="flex justify-between py-1 border-b border-slate-200/60">
                                    <span>B. Indonesia</span>
                                    <span class="font-bold text-slate-900 font-mono">{{ $riwayat->bind_avg }}</span>
                                </div>
                                <div class="flex justify-between py-1 border-b border-slate-200/60">
                                    <span>B. Inggris</span>
                                    <span class="font-bold text-slate-900 font-mono">{{ $riwayat->bing_avg }}</span>
                                </div>
                                <div class="flex justify-between py-1 border-b border-slate-200/60">
                                    <span>Mtk Wajib</span>
                                    <span class="font-bold text-slate-900 font-mono">{{ $riwayat->mtk_wajib_avg }}</span>
                                </div>
                                <div class="flex justify-between py-1 border-b border-slate-200/60">
                                    <span>Mtk Lanjut</span>
                                    <span class="font-bold text-slate-900 font-mono">{{ $riwayat->mtk_lanjut_avg }}</span>
                                </div>
                                <div class="flex justify-between py-1 border-b border-slate-200/60">
                                    <span>Fisika</span>
                                    <span class="font-bold text-slate-900 font-mono">{{ $riwayat->fisika_avg }}</span>
                                </div>
                                <div class="flex justify-between py-1 border-b border-slate-200/60">
                                    <span>Kimia</span>
                                    <span class="font-bold text-slate-900 font-mono">{{ $riwayat->kimia_avg }}</span>
                                </div>
                                <div class="flex justify-between py-1">
                                    <span>Biologi</span>
                                    <span class="font-bold text-slate-900 font-mono">{{ $riwayat->biologi_avg }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Pilihan & Prestasi --}}
                        <div class="space-y-3 flex flex-col justify-between">
                            <div>
                                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-700 flex items-center gap-1.5 mb-3">
                                    <span>🎯</span> Pilihan & Portofolio
                                </h4>
                                <div class="space-y-3">
                                    <div class="p-3 rounded-2xl bg-indigo-50/70 border border-indigo-100">
                                        <span class="text-[10px] font-bold uppercase tracking-wider text-indigo-700">Pilihan 1</span>
                                        <p class="text-xs font-black text-slate-900 mt-0.5">{{ $riwayat->univ_pil1 }}</p>
                                        <p class="text-xs text-slate-600">{{ $riwayat->prodi_pil1 }}</p>
                                        <span class="inline-block mt-1 text-[10px] font-semibold text-indigo-600 bg-white px-2 py-0.5 rounded-md border border-indigo-200">
                                            Keketatan: {{ $riwayat->keketatan_pil1 ?? '-' }}
                                        </span>
                                    </div>

                                    @if($riwayat->univ_pil2)
                                    <div class="p-3 rounded-2xl bg-slate-50 border border-slate-200">
                                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-600">Pilihan 2</span>
                                        <p class="text-xs font-black text-slate-900 mt-0.5">{{ $riwayat->univ_pil2 }}</p>
                                        <p class="text-xs text-slate-600">{{ $riwayat->prodi_pil2 }}</p>
                                        <span class="inline-block mt-1 text-[10px] font-semibold text-slate-600 bg-white px-2 py-0.5 rounded-md border border-slate-200">
                                            Keketatan: {{ $riwayat->keketatan_pil2 ?? '-' }}
                                        </span>
                                    </div>
                                    @endif

                                    <div class="p-3 rounded-2xl bg-amber-50/60 border border-amber-200">
                                        <span class="text-[10px] font-bold uppercase tracking-wider text-amber-700">Prestasi</span>
                                        <p class="text-xs font-bold text-slate-900 mt-0.5">{{ $riwayat->prestasi_tertinggi ?: 'Tidak Ada' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Modal Footer --}}
                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                        <a href="{{ route('siswa.prediksi.hasil', $riwayat->id) }}" class="inline-flex items-center text-xs font-bold text-indigo-600 hover:text-indigo-800 transition-colors">
                            Buka Halaman Laporan Lengkap &rarr;
                        </a>
                        <button type="button" @click="detailModal = null" class="px-5 py-2 text-xs font-bold text-slate-600 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                            Tutup
                        </button>
                    </div>

                </div>
            </div>
        </div>
        @endforeach

    </div>
</x-app-layout>
