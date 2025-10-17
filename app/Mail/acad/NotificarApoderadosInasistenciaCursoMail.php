<?php

namespace App\Mail\acad;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotificarApoderadosInasistenciaCursoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $fecha;
    public $docente;

    public function __construct($data, $fecha, $docente)
    {
        $this->data = $data;
        $this->fecha = $fecha;
        $this->docente = $docente;
    }

    public function build()
    {
        return $this->subject('Notificación por inasistencia')
            ->view('emails.acad.notificar_apoderados_inasistencia_curso')
            ->with([
                'data' => $this->data,
                'fecha' => $this->fecha,
                'docente' => $this->docente,
            ]);
    }
}
