<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\Produk;
use App\Models\WargaBinaan;
use App\Models\SurveyKepuasan;
use App\Models\ProfilLapas;
use App\Models\Informasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $berita = Berita::latest('tanggal')->take(3)->get();
        $produk = Produk::latest('id_produk')->take(4)->get();
        $hunian = WargaBinaan::latest('tanggal_update')->first() ?: (object)[
            'tahanan' => 0, 'narapidana' => 0, 'total_penghuni' => 0, 
            'sidang' => 0, 'berobat_luar' => 0, 'tanggal_update' => date('Y-m-d')
        ];
        $profil = ProfilLapas::first();
        $survey = SurveyKepuasan::where('is_active', 1)->first();
        $informasi = Informasi::latest('id_info')->take(5)->get();
        
        $visitor_count = \App\Models\VisitorLog::count();
        $unique_visitors = \App\Models\VisitorLog::distinct('ip_address')->count();
        $galeri = \App\Models\Galeri::latest('tanggal')->take(6)->get();

        return view('frontend.home', compact('berita', 'produk', 'hunian', 'profil', 'survey', 'visitor_count', 'unique_visitors', 'informasi', 'galeri'));
    }

    public function profile()
    {
        $profil = ProfilLapas::first();
        return view('frontend.profile', compact('profil'));
    }

    public function layanan()
    {
        return view('frontend.layanan');
    }

    public function galeri()
    {
        $galeri = \App\Models\Galeri::published()->latest('tanggal')->paginate(9);
        return view('frontend.galeri', compact('galeri'));
    }

    public function kunjungan()
    {
        return view('frontend.kunjungan');
    }

    public function pengaduan()
    {
        return view('frontend.pengaduan');
    }

    public function produk()
    {
        $produk = Produk::latest('id_produk')->get();
        return view('frontend.produk', compact('produk'));
    }
}
