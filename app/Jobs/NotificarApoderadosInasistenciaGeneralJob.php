<?php

namespace App\Jobs;

use App\Mail\acad\NotificarApoderadosInasistenciaGeneralMail;
use App\Services\asi\AsistenciaGeneralService;
use App\Services\FactilizaService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class NotificarApoderadosInasistenciaGeneralJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;
    protected $fecha;

    public function __construct($data, $fecha)
    {
        $this->data = $data;
        $this->fecha = $fecha;
    }

    public function handle(): void
    {
        foreach ($this->data as $fila) {
            $marcarNotificado = false;
            if (!empty($fila->cPersTelefono)) {
                $nombreApp = config('app.name');
                $mensaje = "Estimado(a) {$fila->cPersNombreApo} {$fila->cPersPaternoApo} {$fila->cPersMaternoApo},
                \nLe informamos que {$fila->cPersNombreEst} {$fila->cPersPaternoEst} {$fila->cPersMaternoEst} no asistió a la institución educativa {$fila->cIieeNombre} el día {$this->fecha}.
                \nSi la inasistencia se debió a un motivo justificado, le agradeceremos que lo comunique a la institución a la brevedad posible.\n\nAtentamente, {$nombreApp}.
                \nEste es un mensaje automático, por favor no responder.";
                FactilizaService::enviarMensajeWhatsApp($fila->cPersTelefono, $mensaje);
                $marcarNotificado = true;
            } else {
                if (filter_var($fila->cPersCorreo, FILTER_VALIDATE_EMAIL)) {
                    Mail::mailer('mailer_noreply')->to($fila->cPersCorreo)->send(new NotificarApoderadosInasistenciaGeneralMail($fila, $this->fecha));
                    $marcarNotificado = true;
                }
            }
            if ($marcarNotificado) {
                AsistenciaGeneralService::marcarAsistenciaGeneralNotificada($fila->idAsistencia);
            }
        }
    }
}
