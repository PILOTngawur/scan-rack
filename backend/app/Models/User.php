<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'FullName',
        'NISNUPTK',
        'email',
        'password',
        'Role',
        'DeviceName',
        'ClassId',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'ClassId');
    }

    public function detailClasses()
    {
        return $this->hasMany(DetailClass::class, 'StudentId');
    }

    public function isAdmin(): bool
    {
        return $this->Role === 'admin';
    }
}