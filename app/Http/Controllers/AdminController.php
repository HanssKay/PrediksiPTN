<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        // Nantinya kamu bisa ambil data statistik dataset di sini
        return view('admin.dashboard');
    }

    
}
