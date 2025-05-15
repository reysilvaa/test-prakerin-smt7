@extends('layouts.app')

@section('content')
<div class="container">
<h2>Departemens List</h2>
<a href="{{ route('departemens.create') }}" class="btn btn-primary mb-3">Create Departemen</a>
<table class="table">
    <thead>
        <tr><th>nama_departemen</th><th>kode_departemen</th><th>deskripsi</th></tr>
    </thead>
    <tbody>
        @foreach ($departemens as $item)
                <tr>
                    <td>{{$item->nama_departemen}}</td>
<td>{{$item->kode_departemen}}</td>
<td>{{$item->deskripsi}}</td>
<td>
                        <a href="{{ route('departemens.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('departemens.destroy', $item->id) }}" method="POST" style="display:inline;">
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