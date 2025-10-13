<?php

namespace App\Jobs;

use App\Mail\acad\NotificarApoderadosInasistenciaCursoMail;
use App\Services\asi\ControlAsistenciaService;
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
            if (filter_var($fila->cPersCorreo, FILTER_VALIDATE_EMAIL)) {
                Mail::mailer('mailer_noreply')->to($fila->cPersCorreo)->send(new NotificarApoderadosInasistenciaCursoMail($fila, $this->fecha, $this->docente));
                ControlAsistenciaService::marcarAsistenciaNotificada($fila->iCtrlAsistenciaId);
            }
        }
    }
}
