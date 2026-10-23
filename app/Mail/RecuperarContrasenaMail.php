<?php

namespace App\Mail;

use App\Models\Empleado;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RecuperarContrasenaMail extends Mailable
{
    use Queueable, SerializesModels;

    public $empleado;
    public $url;

    // Recibe los datos del empleado y el enlace de recuperación con el token
    public function __construct(Empleado $empleado, string $url)
    {
        $this->empleado = $empleado;
        $this->url = $url;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Recuperación de Contraseña - CONABIO Capacitación',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.recuperar_contrasena',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
