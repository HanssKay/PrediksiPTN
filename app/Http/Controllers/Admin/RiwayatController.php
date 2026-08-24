<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RiwayatPrediksi;
use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    public function index(Request $request)
    {
        $query = RiwayatPrediksi::query();

        // Fitur Pencarian sederhana berdasarkan nama
        if ($request->filled('search')) {
            $query->where('nama_siswa', 'like', '%' . $request->search . '%');
        }

        // Urutkan dari yang terbaru
        $riwayats = $query->latest()->paginate(10)->withQueryString();

        return view('admin.riwayat.index', compact('riwayats'));
    }

    public function destroy($id)
    {
        $riwayat = RiwayatPrediksi::findOrFail($id);
        $riwayat->delete();

        return redirect()->route('admin.riwayat.index')->with('success', 'Data riwayat berhasil dihapus.');
    }
}
