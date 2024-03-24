<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Kabupaten;
use App\Models\Provinsi;
use Illuminate\Http\Request;

class Kabupaten_controller extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $provinsiId = $request->input('provinsi');

        $query = Kabupaten::with('provinsi');

        if ($provinsiId) {
            $query->where('provinsi_id', $provinsiId);
        }

        $kabupaten = $query->when($search, function ($query, $search) {
            return $query->where('name', 'like', '%' . $search . '%');
        })
            ->orderBy('name')
            ->paginate(10);

        if (!$provinsiId) {
            $kabupaten = Kabupaten::with('provinsi')
                ->when($search, function ($query, $search) {
                    return $query->where('name', 'like', '%' . $search . '%');
                })
                ->orderBy('name')
                ->paginate(10);
        }

        $provinsi = Provinsi::all();
        $title = 'KABUPATEN';

        return view('kabupaten.index', compact('provinsi', 'kabupaten', 'title'));
    }

    public function create()
    {
        $provinsi = Provinsi::all();
        $title = 'INPUT DATA KABUPATEN';
        return view('kabupaten.create', data: compact('provinsi', 'title'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'provinsi_id' => 'required|exists:provinsi_table,id'
        ]);

        Kabupaten::create([
            'name' => $validatedData['name'],
            'provinsi_id' => $validatedData['provinsi_id']
        ]);

        return redirect()->to('admin-kabupaten')->with('success', 'Data Kabupaten Berhasil Ditambahkan.');
    }

    public function edit(Kabupaten $edit)
    {
        $provinsi = Provinsi::all();
        $title = 'EDIT DATA KABUPATEN';
        return view('kabupaten.edit', data: compact('provinsi', 'edit', 'title'));
    }

    public function update(Kabupaten $edit, Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'provinsi_id' => 'required|exists:provinsi_table,id',
        ]);

        $edit->update([
            'name' => $request->name,
            'provinsi_id' => $request->provinsi_id,
        ]);

        return redirect()->to('admin-kabupaten')->with('success', 'Data Kabupaten Berhasil Diperbarui.');
    }

    public function destroy(Kabupaten $destroy)
    {
        $destroy->delete();
        return redirect()->to('admin-kabupaten')->with('delete', 'Data Kabupaten Telah Dihapus');
    }
}
