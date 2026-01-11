<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use Illuminate\Http\Request;

class BeritaController extends Controller
{
    public function index()
    {
        $berita = Berita::latest('tanggal')->paginate(6);
        return view('frontend.berita.index', compact('berita'));
    }

    public function show($id)
    {
        $berita = Berita::findOrFail($id);
        $rekomendasi = Berita::where('id_berita', '!=', $id)->latest('tanggal')->take(3)->get();
        return view('frontend.berita.show', compact('berita', 'rekomendasi'));
    }
}
