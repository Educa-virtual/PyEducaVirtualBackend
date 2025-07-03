<?php

namespace App\Http\Controllers\seg;

use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use App\Repositories\seg\DatabaseRepository;
use Exception;
use Hashids\Hashids;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class DatabaseController extends Controller
{
    protected $hashids;

    /*public function __construct()
    {
        $this->hashids = new Hashids(config('hashids.salt'), config('hashids.min_length'));
    }*/

    public function realizarBackupBd(Request $request)
    {
        // 1. Ruta absoluta a sqlcmd.exe
        $sqlcmd = 'C:\\Program Files\\Microsoft SQL Server\\Client SDK\\ODBC\\170\\Tools\\Binn\\sqlcmd.exe';

        // 2. Parámetros de entorno y backup
        $server = env('DB_HOST', 'localhost');
        $db     = env('DB_DATABASE');
        $user   = env('DB_USERNAME');
        $pass   = env('DB_PASSWORD');
        $dest   = "D:\\BackupBD\\respaldo_" . now()->format('Ymd_His') . ".bak";

        // 3. Construye el array de comando
        $cmd = [
            $sqlcmd,
            '-S',
            $server,
            '-U',
            $user,
            '-P',
            $pass,
            '-b',           // fuerza errorlevel != 0 en fallo
            '-r1',          // manda errores graves a stderr
            '-Q',
            "BACKUP DATABASE [{$db}] TO DISK = N'{$dest}' WITH INIT, NAME='BackupLaravel'"
        ];

        // 4. Ejecútalo y captura TODO
        $process = new Process($cmd);
        $process->run();        // bloqueante

        // 5. Inspecciona salida y código
        if (! $process->isSuccessful()) {
            $out = trim($process->getOutput());
            $err = trim($process->getErrorOutput());
            throw new \RuntimeException("Backup falló (code {$process->getExitCode()}):\nSTDOUT:\n{$out}\n\nSTDERR:\n{$err}");
        }

        return response()->json([
            'ok'        => true,
            'backup_at' => $dest,
            'output'    => explode("\n", trim($process->getOutput())),
        ]);
        /*try {
            $cmd = [
                'C:\Program Files\Microsoft SQL Server\Client SDK\ODBC\170\Tools\Binn\sqlcmd.exe',
                '-S',
                env('DB_HOST'),
                '-U',
                env('DB_USERNAME'),
                '-P',
                env('DB_PASSWORD'),
                '-b',               // falla en primer error
                '-r1',              // errores graves a stderr
                '-Q',
                "BACKUP DATABASE [" . env('DB_DATABASE') . "] TO DISK=N'D:\\BackupBD\\respaldo_" . now()->format('Ymd_His') . ".bak' WITH INIT"
            ];

            $process = new Process($cmd);
            $process->run();

            if (! $process->isSuccessful()) {
                throw new Exception($process->getExitCode());
                //dd($process->getOutput(), $process->getErrorOutput());
            }
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }*/
    }

    /*public function index()
    {
        $resultado = DatabaseRepository::obtenerHistorialBackups();

        return response()->json([
            'status' => 'Success',
            'message' => 'Se han obtenido los datos de backup correctamente',
            'data' => $resultado
        ], Response::HTTP_OK);
    }*/
}
