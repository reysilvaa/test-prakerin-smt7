<?php

namespace App\Http\Controllers;

use App\Models\Departemen;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Hitung jumlah pegawai dan departemen
        $totalPegawai = Pegawai::count();
        $totalDepartemen = Departemen::count();
        
        // Dapatkan jumlah pegawai per departemen menggunakan query builder
        $pegawaiPerDepartemen = Departemen::select('departemens.id', 'departemens.nama_departemen', DB::raw('count(pegawais.id) as jumlah_pegawai'))
            ->leftJoin('pegawais', 'departemens.id', '=', 'pegawais.departemen_id')
            ->groupBy('departemens.id', 'departemens.nama_departemen')
            ->orderBy('jumlah_pegawai', 'desc')
            ->get();
        
        // Tampilkan view dengan data statistik
        return view('admin.dashboard', compact('totalPegawai', 'totalDepartemen', 'pegawaiPerDepartemen'));
    }
}