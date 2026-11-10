<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ConstanciaObligatorioMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $usuario;
    public $curso;
    public $archivo;

    public function __construct($usuario, $curso, $archivo)
    {
        $this->usuario = $usuario;
        $this->curso = $curso;
        $this->archivo = $archivo;
    }

    public function build()
    {
        $nombreCompleto = trim($this->usuario->nombre . ' ' . ($this->usuario->apellidoP ?? '') . ' ' . ($this->usuario->apellidoM ?? ''));
        $correoEmpleado = $this->usuario->correo;
        $periodo = $this->curso->periodo ?? date('Y');

        return $this->replyTo($correoEmpleado, $nombreCompleto)
                    ->subject('Constancia de curso')
                    ->html("
                        <div style='font-family: Arial, Helvetica, sans-serif; font-size: 14px; line-height: 1.8; color: #222;'>
                            <p>Buenas tardes,</p>

                            <p>
                            Se adjunta la constancia del curso {$this->curso->nombre} para el registro de {$this->curso->horas} horas de capacitación del periodo {$periodo}.
                            <p style='margin: 0;'>Saludos cordiales,</p>
                            <p style='margin: 0;'>{$nombreCompleto}</p>
                            </p>
                        </div>
                    ")
                    ->attach($this->archivo->getRealPath(), [
                        'as'   => $this->archivo->getClientOriginalName(),
                        'mime' => $this->archivo->getMimeType(),
                    ]);
    }
}
