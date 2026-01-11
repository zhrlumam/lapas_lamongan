<?php

namespace App\Http\Controllers;

use App\Models\RatingLayanan;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'nama' => 'nullable|string|max:255',
            'komentar' => 'nullable|string',
            'jenis_layanan' => 'required|string',
        ]);

        RatingLayanan::create($request->all());

        return back()->with('success_rating', 'Terima kasih atas penilaian Anda! Kontribusi Anda sangat berarti bagi peningkatan layanan kami.');
    }
}
