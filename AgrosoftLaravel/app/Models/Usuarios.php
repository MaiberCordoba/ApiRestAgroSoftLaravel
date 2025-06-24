<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;


class Usuarios extends Authenticatable implements JWTSubject 
{
    use HasFactory;

    protected $fillable = [
        'identificacion',
        'nombre',
        'apellidos',
        'fechaNacimiento',
        'telefono',
        'correoElectronico',
        'passwordHash',
        'rol',
        'estado'
    ];

    protected $hidden = ['passwordHash'];

        public function getJWTIdentifier() {
        return $this->getKey();
    }

    public function getJWTCustomClaims() {
        return [];
    }
    
   public function getEmailForPasswordReset()
    {
        return $this->correoElectronico;
    }

    // Especifica el campo de contraseña personalizado
    public function getAuthPassword()
    {
        return $this->passwordHash;
    }
}