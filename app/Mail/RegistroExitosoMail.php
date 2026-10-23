<?php

namespace App\Mail;

use App\Models\Empleado;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RegistroExitosoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $empleado;

    public function __construct(Empleado $empleado)
    {
        $this->empleado = $empleado;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Registro Exitoso al Sistema de Capacitación - CONABIO',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.registro_exitoso',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
