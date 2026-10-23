<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Mail\RecuperarContrasenaMail;
use Illuminate\Support\Facades\Mail;

class Empleado extends Authenticatable
{
    use Notifiable;

    protected $table = 'empleado';

    protected $primaryKey = 'idEmpleado';

    public $timestamps = false;

    protected $fillable = [
        'idEmpleado',
        'nombre',
        'apellidoP',
        'apellidoM',
        'correo',
        'contrasena',
        'idRol',
    ];

    protected $hidden = [
        'contrasena',
    ];

    public function getAuthPassword()
    {
        return $this->contrasena;
    }

    public function getRememberTokenName()
    {
        return null;
    }


    public function getEmailForPasswordReset()
    {
        return $this->correo;
    }

    public function routeNotificationForMail($notification = null)
    {
        return $this->correo;
    }

     public function sendPasswordResetNotification($token)
    {
        $url = route('password.reset', [
            'token' => $token,
            'email' => $this->getEmailForPasswordReset(),
        ]);

        Mail::to($this->correo)->send(new RecuperarContrasenaMail($this, $url));
    }

    public function cursos()
    {
        return $this->hasMany(\App\Models\Curso::class, 'idEmpleado', 'idEmpleado');
    }
}
