<?php

namespace App\Mail\seg;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordCambiadoMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $usuario;

    public $token;

    public function __construct($usuario)
    {
        $this->usuario = $usuario;
    }

    public function build()
    {
        return $this->subject('Contraseña actualizada')
            ->view('emails.seg.password_cambiado')
            ->with([
                'usuario' => $this->usuario,
            ]);
    }
}
