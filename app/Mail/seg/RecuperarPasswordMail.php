<?php

namespace App\Mail\seg;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RecuperarPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */

    public $usuario;
    public $token;

    public function __construct($usuario, $token)
    {
        $this->usuario = $usuario;
        $this->token = $token;
    }

    public function build()
    {
        return $this->subject('Recuperación de contraseña')
                    ->view('emails.seg.password_recovery')
                    ->with([
                        'usuario' => $this->usuario,
                        'token' => $this->token
                    ]);
    }
}
