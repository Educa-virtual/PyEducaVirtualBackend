<?php

namespace App\Services;

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
    public static function parse($error_message)
    {
        // Ubicar última instancia de ] en mensaje
        $pos_inicio = strripos($error_message, ']') + 1;
        $pos_cierre = stripos($error_message, '(Connection: sqlsrv');


        $return_message = substr($error_message, $pos_inicio, $pos_cierre - $pos_inicio);

        return $return_message;
    }
}