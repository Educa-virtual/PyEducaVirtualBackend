<?php

namespace App\Repositories\seg;

use Carbon\Carbon;
use DragonCode\Support\Facades\Filesystem\File;
use Illuminate\Support\Facades\DB;

class DatabaseRepository
{
    public static function backupDatabase($nombreBd, $ruta, $iPersId)
    {
        $server = env('DB_HOST', 'localhost');
        $database = env('DB_DATABASE');
        $user = env('DB_USERNAME');
        $password = env('DB_PASSWORD');

        $backupFile = 'resp_' . now()->format('Ymd_His') . '.bak';
        $backupPath = 'D:\\BackupBD\\' . $backupFile;

        $batContent = <<<BAT
@echo off
sqlcmd -S "$server" -U "$user" -P "$password" -Q "BACKUP DATABASE [$database] TO DISK = N'$backupPath' WITH INIT, NAME = 'Backup desde Laravel'"
BAT;

        $batFilePath = storage_path('app/backup_sql.bat');
        File::put($batFilePath, $batContent);

        // Ejecutar el archivo .bat
        $output = [];
        $result = null;
        exec("start /b \"\" \"$batFilePath\"", $output, $result);

        return $result === 0
            ? "Respaldo ejecutado. Se guardará en: $backupPath"
            : "Error al ejecutar el respaldo. Código de salida: $result";
        /*$nombreBackup = $nombreBd . '_backup_' . date('Y_m_d_His') . '.bak';
        $database = env('DB_DATABASE');
        $backupPath = 'D:\\BackupBD\\backup.bak';

        //$sql = "BACKUP DATABASE [$database] TO DISK = N'$backupPath' WITH INIT, NAME = 'Full Backup of $database'";
        $sql="BACKUP DATABASE [CINFODW] TO DISK = N'D:\BackupBD\archivo.bak';";
        //file_put_contents("D:\\backup.txt",$sql);
        DB::statement($sql);*/

        //DB::statement('EXEC [seg].usp_BackupDatabase @DatabaseName=?, @BackupFileName=?, @BackupPath=?', [$nombreBd, $nombreBackup, $ruta]);
        /*DB::statement('INSERT INTO [seg].[backup_logs]
           (cBackupNombre,cBackupPath,iPersId,dtBackupCreacion,cBackupStatus)
           VALUES (?, ?, ?, GETDATE(), ?)', [$nombreBackup, $ruta, $iPersId, 'Completado']);*/
    }

    public static function obtenerHistorialBackups()
    {
        $resultado = DB::select("SELECT iBackupId,cBackupNombre, cBackupPath, cBackupStatus, CONCAT(cPersNombre,' ',cPersPaterno,' ',cPersMaterno) AS cPersNombre,
        FORMAT(dtBackupCreacion, 'dd/MM/yyyy HH:mm') AS dtBackupCreacion,
        (?-DATEDIFF(DAY, dtBackupCreacion, GETDATE())) AS iDiasParaEliminarse
        FROM seg.backup_logs AS bl
        INNER JOIN grl.personas AS p ON p.iPersId=bl.iPersId
        ORDER BY dtBackupCreacion DESC", [env('DB_DIAS_BACKUP')]);
        foreach ($resultado as $fila) {
            $fila->iDiasParaEliminarse = $fila->iDiasParaEliminarse < 0 ? 0 : $fila->iDiasParaEliminarse; //file_exists($fila->cBackupPath.'\\'.$fila->cBackupNombre) ? 'SI' : 'NO';
        }
        return $resultado;
    }
}
