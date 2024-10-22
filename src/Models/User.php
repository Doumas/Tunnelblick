<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class User extends Model
{
    protected $table = 'Users';
    protected $primaryKey = 'user_id';
    protected $fillable = [
        'username', 'password', 'email', 'role'
    ];

    // Automatisches Hashen des Passworts beim Setzen
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    // Überprüfung des Passworts
    public function verifyPassword($password)
    {
        return Hash::check($password, $this->attributes['password']);
    }
}
