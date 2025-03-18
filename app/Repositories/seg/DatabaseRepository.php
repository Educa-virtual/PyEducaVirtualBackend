<?php

namespace App\Repositories\seg;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DatabaseRepository
{
    public static function backupDatabase($nombreBd, $ruta, $iPersId)
    {
        $nombreBackup = $nombreBd . '_backup_' . date('Y_m_d_His') . '.bak';
        DB::statement('EXEC [seg].usp_BackupDatabase @DatabaseName=?, @BackupFileName=?, @BackupPath=?', [$nombreBd, $nombreBackup, $ruta]);
        DB::statement('INSERT INTO [seg].[backup_logs]
           (cBackupNombre,cBackupPath,iPersId,dtBackupCreacion,cBackupStatus)
           VALUES (?, ?, ?, GETDATE(), ?)', [$nombreBackup, $ruta, $iPersId, 'Completado']);
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
