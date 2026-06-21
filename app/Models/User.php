<?php

namespace App\Models;

// Исправьте импорты - уберите HasApiTokens если нет Laravel Sanctum
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;  // Убрали HasApiTokens

    // app/Models/User.php
    protected $fillable = [
        'name', 'email', 'password', 'is_admin', 'phone', 'address'
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    public function isAdmin()
    {
        return $this->is_admin === true;
    }

    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }


}
