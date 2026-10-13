<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class DatasetController extends Controller
{
    public function index()
    {
        return redirect()->route('admin.c45.dataset');
    }
}