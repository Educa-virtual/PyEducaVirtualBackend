<?php

namespace App\Mail\cap;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EstadoInscripcionMail extends Mailable
{
  use Queueable, SerializesModels;

  public $participante;
  public $capacitacion;
  public $estado; // 'aprobado' o 'rechazado'

  public function __construct($participante, $capacitacion, $estado)
  {
    $this->participante = $participante;
    $this->capacitacion = $capacitacion;
    $this->estado = $estado;
  }

  public function build()
  {
    $asunto = $this->estado === 'aprobado'
      ? '¡Tu inscripción ha sido aprobada!'
      : 'Actualización sobre tu inscripción';

    return $this->subject($asunto)
      ->view('emails.cap.estado_inscripcion')
      ->with([
        'participante' => $this->participante,
        'capacitacion' => $this->capacitacion,
        'estado' => $this->estado
      ]);
  }
}
