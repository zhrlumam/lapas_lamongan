<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WargaBinaan;
use Illuminate\Http\Request;

class HunianController extends Controller
{
    public function index()
    {
        $hunian = WargaBinaan::latest('tanggal_update')->get();
        $latest = WargaBinaan::latest('tanggal_update')->first();
        return view('admin.hunian.index', compact('hunian', 'latest'));
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $data['total_penghuni'] = $request->tahanan + $request->narapidana;
        $data['tanggal_update'] = date('Y-m-d');
        
        WargaBinaan::create($data);
        return redirect()->back()->with('success', 'Data hunian berhasil diperbarui');
    }
}
