<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RatingLayanan;
use Illuminate\Http\Request;

class RatingAdminController extends Controller
{
    public function index()
    {
        $ratings = RatingLayanan::latest()->paginate(10);
        
        // Summary stats
        $stats = [
            'total' => RatingLayanan::count(),
            'average' => round(RatingLayanan::avg('rating'), 1),
            'last_month' => RatingLayanan::where('created_at', '>=', now()->subMonth())->count()
        ];

        return view('admin.rating.index', compact('ratings', 'stats'));
    }

    public function destroy($id)
    {
        RatingLayanan::findOrFail($id)->delete();
        return back()->with('success', 'Data penilaian berhasil dihapus.');
    }
}
