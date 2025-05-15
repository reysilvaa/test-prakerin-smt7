<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\PegawaiRequest;
use App\Models\Pegawai;

class PegawaiController extends Controller
{
    public function index(): \Illuminate\Contracts\View\View
    {
        $pegawais = Pegawai::select('pegawais.*', 'departemens.nama_departemen')
            ->join('departemens', 'pegawais.departemen_id', '=', 'departemens.id')
            ->latest()
            ->paginate(10);
        return view('pegawais.index', compact('pegawais'));
    }

    public function create(): \Illuminate\Contracts\View\View
    {
        return view('pegawais.create');
    }

    public function store(PegawaiRequest $request): \Illuminate\Http\RedirectResponse
    {
        Pegawai::create($request->validated());
        return redirect()->route('pegawais.index')->with('success', 'Created successfully');
    }

    public function show(Pegawai $pegawai): \Illuminate\Contracts\View\View
    {
        return view('pegawais.show', compact('pegawai'));
    }

    public function edit(Pegawai $pegawai): \Illuminate\Contracts\View\View
    {
        return view('pegawais.edit', compact('pegawai'));
    }

    public function update(PegawaiRequest $request, Pegawai $pegawai): \Illuminate\Http\RedirectResponse
    {
        $pegawai->update($request->validated());
        return redirect()->route('pegawais.index')->with('success', 'Updated successfully');
    }

    public function destroy(Pegawai $pegawai): \Illuminate\Http\RedirectResponse
    {
        $pegawai->delete();
        return redirect()->route('pegawais.index')->with('success', 'Deleted successfully');
    }
}
