<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ParseSqlErrorService
{
    public function __invoke($error_message)
    {
        return $this->parse($error_message);
    }

    /*
        Formatear mensaje de error obntenido de SQL Server:
        SQLSTATE[#####]: [Microsoft][ODBC Driver ## for SQL Server][SQL Server] Mensaje de error (Connection: sqlsrv)

        Input: texto verboso del error emitido por SQL Server
        Output: Mensaje de error sin texto verboso
    */
    public static function parse($error)
    {
        if( is_array($error) ) {
            $error_message = $error['errorInfo'][2];
        } elseif ( is_string($error) ) {
            $error_message = $error;
        } else {
            $error_message = json_encode($error);
        }

        // Ubicar última instancia de ] en mensaje
        $pos_inicio = strripos($error_message, 'SQL Server]') + 11;
        $pos_cierre = stripos($error_message, '(Connection: sqlsrv');


        $return_message = substr($error_message, $pos_inicio, $pos_cierre - $pos_inicio);

        return $return_message;
    }
}