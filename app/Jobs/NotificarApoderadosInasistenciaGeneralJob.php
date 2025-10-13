<?php

namespace App\Jobs;

use App\Mail\acad\NotificarApoderadosInasistenciaGeneralMail;
use App\Services\asi\AsistenciaGeneralService;
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
            if (filter_var($fila->cPersCorreo, FILTER_VALIDATE_EMAIL)) {
                Mail::mailer('mailer_noreply')->to($fila->cPersCorreo)->send(new NotificarApoderadosInasistenciaGeneralMail($fila, $this->fecha));
                AsistenciaGeneralService::marcarAsistenciaGeneralNotificada($fila->idAsistencia);
            }
        }
    }
}
