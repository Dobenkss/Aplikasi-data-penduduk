<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Provinsi;
use Illuminate\Http\Request;

class Provinsi_controller extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $provinsi = Provinsi::query()
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', '%' . $search . '%');
            })
            ->orderBy('name')
            ->paginate(10);

        $title = 'PROVINSI';
        return view('provinsi.index', data: compact('provinsi', 'title'));
    }

    public function create()
    {
        $title = 'INPUT DATA PROVINSI';
        return view('provinsi.create', data: compact('title'));
    }

    public function store(Request $request)
    {
        Provinsi::create([
            'name' => $request->name
        ]);
        return redirect()->to('admin-provinsi')->with('success', 'Data Provinsi Berhasil Ditambahkan.');
    }

    public function edit(Provinsi $edit)
    {
        $title = 'EDIT DATA PROVINSI';
        return view('provinsi.edit', data: compact('edit', 'title'));
    }

    public function update(Request $request, Provinsi $edit)
    {
        $edit->update([
            'name' => $request->name
        ]);
        return redirect()->to('admin-provinsi')->with('success', 'Data Provinsi Berhasil Diperbarui.');
    }

    public function destroy(Provinsi $destroy)
    {
        $destroy->delete();
        return redirect()->to('admin-provinsi')->with('delete', 'Data Provinsi Telah Dihapus.');
    }
}
