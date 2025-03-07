<?php

namespace App\Repositories\seg;

use Illuminate\Support\Facades\DB;

class DatabaseRepository {
    public static function backupDatabase($base, $ruta) {
        DB::statement('EXEC [seg].usp_BackupDatabase ?, ?', [$base, $ruta]);
        self::registrarBackup($base, $ruta);
    }

    public static function registrarBackup($base, $ruta) {
        DB::statement('EXEC [seg].usp_RegistrarBackup ?, ?', [$base, $ruta]);
    }
}
