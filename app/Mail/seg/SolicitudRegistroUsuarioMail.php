<?php

namespace App\Mail\seg;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SolicitudRegistroUsuarioMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */

    public $nombre;

    public function __construct($nombre)
    {
        $this->nombre = $nombre;
    }

    public function build()
    {
        return $this->subject('Solicitud de registro de usuario')
                    ->view('emails.seg.solicitud_registro_usuario')
                    ->with([
                        'nombre' => $this->nombre,
                    ]);
    }
}
