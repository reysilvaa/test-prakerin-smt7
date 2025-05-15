<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PegawaiDashboardController extends Controller
{
    public function index()
    {
        $pegawai = auth()->user()->pegawai;
        return view('pegawai.dashboard', compact('pegawai'));
    }
}