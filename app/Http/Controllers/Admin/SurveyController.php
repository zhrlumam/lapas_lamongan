<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SurveyKepuasan;
use Illuminate\Http\Request;

class SurveyController extends Controller
{
    public function index()
    {
        $surveys = SurveyKepuasan::orderBy('id_survey', 'desc')->get();
        return view('admin.survey.index', compact('surveys'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'bulan' => 'required',
            'skor_ipk' => 'required|numeric',
            'skor_ikm' => 'required|numeric',
            'jumlah_responden' => 'required|integer|min:0',
            'keterangan' => 'required|in:Sangat Baik,Baik,Cukup,Kurang',
        ]);

        SurveyKepuasan::create([
            'bulan' => $request->bulan,
            'skor_ipk' => $request->skor_ipk,
            'skor_ikm' => $request->skor_ikm,
            'jumlah_responden' => $request->jumlah_responden,
            'keterangan' => $request->keterangan,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);

        if ($request->has('is_active')) {
            $this->setActive(SurveyKepuasan::latest('id_survey')->first()->id_survey);
        }

        return back()->with('success', 'Data survey berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'bulan' => 'required',
            'skor_ipk' => 'required|numeric',
            'skor_ikm' => 'required|numeric',
            'jumlah_responden' => 'required|integer|min:0',
            'keterangan' => 'required|in:Sangat Baik,Baik,Cukup,Kurang',
        ]);

        $survey = SurveyKepuasan::findOrFail($id);
        $survey->update([
            'bulan' => $request->bulan,
            'skor_ipk' => $request->skor_ipk,
            'skor_ikm' => $request->skor_ikm,
            'jumlah_responden' => $request->jumlah_responden,
            'keterangan' => $request->keterangan,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);

        if ($request->has('is_active')) {
            $this->setActive($id);
        }

        return redirect()->route('admin.survey.index')->with('success', 'Data survey berhasil diperbarui.');
    }

    public function activate($id)
    {
        $this->setActive($id);
        return back()->with('success', 'Survey berhasil diaktifkan untuk tampilan publik.');
    }

    public function destroy($id)
    {
        SurveyKepuasan::where('id_survey', $id)->delete();
        return back()->with('success', 'Data survey berhasil dihapus.');
    }

    private function setActive($id)
    {
        SurveyKepuasan::query()->update(['is_active' => 0]);
        SurveyKepuasan::where('id_survey', $id)->update(['is_active' => 1]);
    }
}
