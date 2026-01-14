<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
            
            // Cek apakah user sudah ada berdasarkan google_id atau email
            $user = User::where('google_id', $googleUser->id)
                        ->orWhere('email', $googleUser->email)
                        ->first();

            if ($user) {
                // Jika user ada, update google_id (untuk kasus match by email) dan avatar
                $user->update([
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar
                ]);
            } else {
                // Jika user belum ada, buat baru
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar,
                    'password' => bcrypt(Str::random(16)), // Random password
                    'role' => 'user'
                ]);
            }

            // Login user
            Auth::guard('penjamin')->login($user);

            // Cek apakah data NIK & WBP sudah lengkap
            if (!$user->nik || !$user->nama_wbp) {
                return redirect()->route('integrasi.complete_profile');
            }

            // Redirect ke halaman Integrasi (dashboard)
            return redirect()->route('integrasi.dashboard'); 

        } catch (\Exception $e) {
            // SECURITY FIX: Generic error message untuk production
            \Illuminate\Support\Facades\Log::error('Google Login Error: ' . $e->getMessage());
            return redirect()->route('integrasi.login')
                ->with('error', 'Gagal login dengan Google. Silakan coba lagi atau hubungi administrator.');
        }
    }
}
