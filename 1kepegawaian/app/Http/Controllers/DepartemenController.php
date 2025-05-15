<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\DepartemenRequest;
use App\Models\Departemen;

class DepartemenController extends Controller
{
    public function index(): \Illuminate\Contracts\View\View
    {
        $departemens = Departemen::latest()->paginate(10);
        return view('departemens.index', compact('departemens'));
    }

    public function create(): \Illuminate\Contracts\View\View
    {
        return view('departemens.create');
    }

    public function store(DepartemenRequest $request): \Illuminate\Http\RedirectResponse
    {
        Departemen::create($request->validated());
        return redirect()->route('departemens.index')->with('success', 'Created successfully');
    }

    public function show(Departemen $departemen): \Illuminate\Contracts\View\View
    {
        return view('departemens.show', compact('departemen'));
    }

    public function edit(Departemen $departemen): \Illuminate\Contracts\View\View
    {
        return view('departemens.edit', compact('departemen'));
    }

    public function update(DepartemenRequest $request, Departemen $departemen): \Illuminate\Http\RedirectResponse
    {
        $departemen->update($request->validated());
        return redirect()->route('departemens.index')->with('success', 'Updated successfully');
    }

    public function destroy(Departemen $departemen): \Illuminate\Http\RedirectResponse
    {
        $departemen->delete();
        return redirect()->route('departemens.index')->with('success', 'Deleted successfully');
    }
}
