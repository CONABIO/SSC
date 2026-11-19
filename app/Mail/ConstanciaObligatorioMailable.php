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
    public $fromEmail;
    public $fromName;
    public $esComprobante;

    public function __construct(
        $usuario,
        $curso,
        $archivo,
        $fromEmail = null,
        $fromName = null,
        $esComprobante = false
    ) {
        $this->usuario = $usuario;
        $this->curso = $curso;
        $this->archivo = $archivo;
        $this->fromEmail = $fromEmail;
        $this->fromName = $fromName;
        $this->esComprobante = $esComprobante;
    }

    public function build()
    {
        $nombreCompleto = trim(
            ($this->usuario->nombre ?? $this->usuario->name ?? '') . ' ' .
            ($this->usuario->apellidoP ?? '') . ' ' .
            ($this->usuario->apellidoM ?? '')
        );
        $correoEmpleado = trim($this->usuario->correo ?? $this->usuario->email ?? '');
        $periodo = $this->curso->periodo ?? date('Y');

        if ($this->fromEmail && filter_var($this->fromEmail, FILTER_VALIDATE_EMAIL)) {
            $fromAddress = $this->fromEmail;
            $fromDisplayName = $this->fromName ?: config('mail.from.name');
        } else {
            $fromAddress = filter_var($correoEmpleado, FILTER_VALIDATE_EMAIL)
                ? $correoEmpleado
                : config('mail.from.address');
            $fromDisplayName = $nombreCompleto;
        }

        if ($this->esComprobante) {
            $subject = 'Comprobante de envío: Constancia de curso';
            $html = "
                <div style='font-family: Arial, Helvetica, sans-serif; font-size: 14px; line-height: 1.8; color: #222;'>
                    <p>Hola {$nombreCompleto},</p>

                    <p>
                        Este correo es tu comprobante de que se envió la constancia del curso
                        <strong>{$this->curso->nombre}</strong> ({$this->curso->horas} horas, periodo {$periodo})
                        a <strong>centro.capacitacion@semarnat.gob.mx</strong>.
                    </p>

                    <p style='margin: 0;'>Atentamente,</p>
                    <p style='margin: 0;'>Talento Humano CONABIO</p>
                </div>
            ";
        } else {
            $subject = 'Constancia de curso';
            $html = "
                <div style='font-family: Arial, Helvetica, sans-serif; font-size: 14px; line-height: 1.8; color: #222;'>
                    <p>Buen día,</p>

                    <p>
                        Se adjunta la constancia del curso {$this->curso->nombre} para el registro de {$this->curso->horas} horas de capacitación del periodo {$periodo}.
                    </p>

                    <p style='margin: 0;'>Saludos cordiales,</p>
                    <p style='margin: 0;'>{$nombreCompleto}</p>
                </div>
            ";
        }

        $mail = $this->from($fromAddress, $fromDisplayName)
                    ->subject($subject)
                    ->html($html);

        if ($this->archivo) {
            $mail->attach($this->archivo->getRealPath(), [
                'as'   => $this->archivo->getClientOriginalName(),
                'mime' => $this->archivo->getMimeType(),
            ]);
        }

        return $mail;
    }
}
