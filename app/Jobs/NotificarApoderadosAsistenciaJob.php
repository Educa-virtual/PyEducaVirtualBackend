<?php

namespace App\Jobs;

use App\Mail\acad\NotificarApoderadosAsistenciaMail;
use App\Mail\NotificarUsuarios;
use App\Services\asi\AsistenciaGeneralService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class NotificarApoderadosAsistenciaJob implements ShouldQueue
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
                Mail::mailer('mailer_noreply')->to($fila->cPersCorreo)->send(new NotificarApoderadosAsistenciaMail($fila, $this->fecha));
                AsistenciaGeneralService::marcarNotificado($fila->idAsistencia);
                //file_put_contents('d:/asi_log.txt', date('Y-m-d H:i:s') . " - ID: {$fila->idAsistencia} \n", FILE_APPEND);
            }
        }
    }
}
