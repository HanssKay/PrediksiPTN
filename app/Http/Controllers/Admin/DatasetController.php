<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DatasetPrediksi;
use Illuminate\Http\Request;

class DatasetController extends Controller
{
    public function index()
    {
        // Mengambil data dengan paginasi agar tidak berat (10 data per halaman)
        $datasets = DatasetPrediksi::latest()->paginate(10);
        
        return view('admin.dataset.index', compact('datasets'));
    }
}