@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard Pegawai') }}</div>

                <div class="card-body">
                    <h2>Selamat datang, {{ $pegawai->nama }}!</h2>
                    
                    <div class="mt-4">
                        <h4>Informasi Pegawai</h4>
                        <table class="table table-bordered">
                            <tr>
                                <th width="30%">NIP</th>
                                <td>{{ $pegawai->nip }}</td>
                            </tr>
                            <tr>
                                <th>Nama</th>
                                <td>{{ $pegawai->nama }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $pegawai->email }}</td>
                            </tr>
                            <tr>
                                <th>Departemen</th>
                                <td>{{ $pegawai->departemen->nama_departemen }}</td>
                            </tr>
                            <tr>
                                <th>Jabatan</th>
                                <td>{{ $pegawai->jabatan }}</td>
                            </tr>
                            <tr>
                                <th>Status Kepegawaian</th>
                                <td>{{ $pegawai->status_kepegawaian }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Bergabung</th>
                                <td>{{ $pegawai->tanggal_bergabung }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection