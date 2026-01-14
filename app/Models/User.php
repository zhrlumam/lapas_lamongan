<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'nik',
        'nama_wbp',
        'password',
        'google_id',
        'avatar',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Check if user is Super Admin
     */
    public function isSuper()
    {
        return $this->role === 'super_admin';
    }

    /**
     * Check if user has specific role
     */
    public function hasRole($role)
    {
        // normalize role string
        return strtolower($this->role) === strtolower($role);
    }

    /**
     * Check if user has Layanan access
     */
    public function isLayanan()
    {
        return $this->hasRole('layanan') || $this->isSuper();
    }
}
