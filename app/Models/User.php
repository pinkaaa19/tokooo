<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable // <--- Harus meng-extend ini
{
    use Notifiable;

    // Pastikan field ini ada agar bisa diisi
    protected $fillable = [
        'name', 'email', 'password', 'phone_number', 'profile_photo','role'
    ];

public function preferences()
{
    return $this->hasMany(Preference::class);
}
}
