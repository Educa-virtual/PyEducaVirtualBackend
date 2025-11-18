<?php

namespace App\Jobs;

use App\Mail\acad\NotificarApoderadosInasistenciaCursoMail;
use App\Services\asi\ControlAsistenciaService;
use App\Services\FactilizaService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class NotificarApoderadosInasistenciaCursoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;
    protected $fecha;
    protected $docente;

    public function __construct($data, $fecha, $docente)
    {
        $this->data = $data;
        $this->fecha = $fecha;
        $this->docente = $docente;
    }

    public function handle(): void
    {
        foreach ($this->data as $fila) {
            $marcarNotificado = false;
            if (!empty($fila->cPersTelefono)) {
                $nombreApp = config('app.name');
                $mensaje = "Estimado(a) {$fila->cPersNombreApo} {$fila->cPersPaternoApo} {$fila->cPersMaternoApo},
                \nLe informamos que {$fila->cPersNombreEst} {$fila->cPersPaternoEst} {$fila->cPersMaternoEst} no asistió al área curricular de  {$fila->cCursoNombre} el día {$this->fecha}, dictada por {$this->docente->cPersNombre} {$this->docente->cPersPaterno} {$this->docente->cPersMaterno}, en la institución educativa {$fila->cIieeNombre}.
                \nSi la inasistencia se debió a un motivo justificado, le agradeceremos que lo comunique a la institución a la brevedad posible.
                \nAtentamente, {$nombreApp}.
                \nEste es un mensaje automático, por favor no responder.";
                FactilizaService::enviarMensajeWhatsApp($fila->cPersTelefono, $mensaje);
                $marcarNotificado = true;
            } else {
                if (filter_var($fila->cPersCorreo, FILTER_VALIDATE_EMAIL)) {
                    Mail::mailer('mailer_noreply')->to($fila->cPersCorreo)->send(new NotificarApoderadosInasistenciaCursoMail($fila, $this->fecha, $this->docente));
                    $marcarNotificado = true;
                }
            }
            if ($marcarNotificado) {
                ControlAsistenciaService::marcarAsistenciaNotificada($fila->iCtrlAsistenciaId);
            }
        }
    }
}
