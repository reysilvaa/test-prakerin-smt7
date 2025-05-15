@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <h1 class="mb-4">Dashboard Admin</h1>
            
            <!-- Statistik Utama -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h5 class="card-title">Total Pegawai</h5>
                            <h2 class="display-4">{{ $totalPegawai }}</h2>
                            <p class="card-text">Jumlah semua pegawai yang terdaftar dalam sistem</p>
                            <a href="{{ route('pegawais.index') }}" class="btn btn-light">Lihat Detail</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h5 class="card-title">Total Departemen</h5>
                            <h2 class="display-4">{{ $totalDepartemen }}</h2>
                            <p class="card-text">Jumlah semua departemen yang ada dalam sistem</p>
                            <a href="{{ route('departemens.index') }}" class="btn btn-light">Lihat Detail</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Tabel Jumlah Pegawai per Departemen -->
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Jumlah Pegawai per Departemen</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Departemen</th>
                                    <th class="text-center">Jumlah Pegawai</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pegawaiPerDepartemen as $departemen)
                                <tr>
                                    <td>{{ $departemen->nama_departemen }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-primary rounded-pill">{{ $departemen->jumlah_pegawai }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('departemens.show', $departemen->id) }}" class="btn btn-sm btn-info">Detail</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection