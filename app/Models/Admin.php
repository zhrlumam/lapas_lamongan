<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $table = 'admin';
    protected $primaryKey = 'id_admin';
    public $timestamps = false;

    protected $fillable = [
        'nama',
        'username',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
    ];

    public function isSuper()
    {
        return $this->role === 'Super Admin' || $this->role === 'Super';
    }

    public function isHumas()
    {
        return $this->role === 'Humas';
    }

    public function isPengaduan()
    {
        return $this->role === 'Pengaduan';
    }

    public function isLayanan()
    {
        return $this->role === 'Layanan' || $this->role === 'Registrasi';
    }

    public function hasRole($role)
    {
        if ($this->isSuper()) return true;
        return $this->role === $role;
    }
}
