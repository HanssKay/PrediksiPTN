<?php

namespace App\Http\Controllers;

use App\Models\DatasetPrediksi;
use App\Models\RiwayatPrediksi;
use App\Models\User;
use App\Models\C45Rule;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $totalDataset = DatasetPrediksi::count();
        $totalLolos = DatasetPrediksi::where('status', 'LOLOS')->count();
        $totalTidakLolos = DatasetPrediksi::where('status', 'TIDAK LOLOS')->count();
        $totalRiwayat = RiwayatPrediksi::count();
        $totalSiswa = User::where('role', 'siswa')->count();
        $totalRules = C45Rule::count();
        $recentRiwayats = RiwayatPrediksi::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalDataset',
            'totalLolos',
            'totalTidakLolos',
            'totalRiwayat',
            'totalSiswa',
            'totalRules',
            'recentRiwayats'
        ));
    }
}
