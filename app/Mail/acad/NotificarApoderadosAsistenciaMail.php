<?php

namespace App\Mail\acad;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotificarApoderadosAsistenciaMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $fecha;

    public function __construct($data, $fecha)
    {
        $this->data = $data;
        $this->fecha = $fecha;
    }

    public function build()
    {
        return $this->subject('Notificación por inasistencia')
            ->view('emails.acad.notificar_apoderados_asistencia')
            ->with([
                'data' => $this->data,
                'fecha' => $this->fecha
            ]);
    }
}
