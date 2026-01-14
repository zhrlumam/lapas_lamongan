<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use Illuminate\Http\Request;

class BeritaController extends Controller
{
    public function index()
    {
        $berita = Berita::published()->latest('tanggal')->paginate(6);
        return view('frontend.berita.index', compact('berita'));
    }

    public function show($slug)
    {
        // Fixed: Prevent scope conflict with orWhere
        $berita = Berita::published()
            ->where(function($q) use ($slug) {
                $q->where('slug', $slug)->orWhere('id_berita', $slug);
            })
            ->firstOrFail();

        // Redirect if accessed via ID for SEO
        if (is_numeric($slug)) {
            return redirect()->route('berita.show', $berita);
        }

        $rekomendasi = Berita::published()->where('slug', '!=', $slug)->latest('tanggal')->take(3)->get();
        return view('frontend.berita.show', compact('berita', 'rekomendasi'));
    }
}
