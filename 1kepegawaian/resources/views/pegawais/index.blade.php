@extends('layouts.app')
@section('content')
<div class="container">
<h2>pegawais List</h2>
<a href="{{ route('pegawais.create') }}" class="btn btn-primary mb-3">Create pegawais</a>
<table class="table">
    <thead>
        <tr><th>nama</th><th>nip</th><th>email</th><th>no_telepon</th><th>alamat</th><th>tanggal_lahir</th><th>jenis_kelamin</th><th>nama_departemen?</th><th>jabatan</th><th>tanggal_bergabung</th><th>status_kepegawaian</th><th>gaji</th></tr>
    </thead>
    <tbody>
        @foreach ($pegawais as $item)
                <tr>
                    <td>{{$item->nama}}</td>
                    <td>{{$item->nip}}</td>
                    <td>{{$item->email}}</td>
                    <td>{{$item->no_telepon}}</td>
                    <td>{{$item->alamat}}</td>
                    <td>{{$item->tanggal_lahir}}</td>
                    <td>{{$item->jenis_kelamin}}</td>
                    <td>{{$item->departemen->nama_departemen}}</td>
                    <td>{{$item->jabatan}}</td>
                    <td>{{$item->tanggal_bergabung}}</td>
                    <td>{{$item->status_kepegawaian}}</td>
                    <td>{{$item->gaji}}</td>
                    <td>
                        <a href="{{ route('pegawais.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('pegawais.destroy', $item->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection