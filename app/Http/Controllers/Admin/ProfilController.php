<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProfilLapas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfilController extends Controller
{
    public function index()
    {
        $profil = ProfilLapas::first() ?? new ProfilLapas();
        return view('admin.profil.index', compact('profil'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nama_instansi' => 'required|string|max:255',
            'deskripsi_singkat' => 'required|string',
            'nama_kepala' => 'required|string|max:255',
            'jabatan_kepala' => 'nullable|string|max:255',
            'visi' => 'required|string',
            'misi' => 'required|string',
            'sejarah' => 'nullable|string',
            'sambutan_kepala' => 'nullable|string',
            'foto_kepala' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->except(['foto_kepala', '_token']);
        $profil = ProfilLapas::first();

        if ($request->hasFile('foto_kepala')) {
            // Hapus foto lama jika ada
            if ($profil && $profil->foto_kepala) {
                if (Storage::disk('public')->exists($profil->foto_kepala)) {
                    Storage::disk('public')->delete($profil->foto_kepala);
                }
            }
            
            // Simpan foto baru
            $path = $request->file('foto_kepala')->store('profil', 'public');
            $data['foto_kepala'] = $path;
        }

        if ($profil) {
            $profil->update($data);
        } else {
            ProfilLapas::create($data);
        }

        return redirect()->back()->with('success', 'Profil berhasil diperbarui');
    }
}
