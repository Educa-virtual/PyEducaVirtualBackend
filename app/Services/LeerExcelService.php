<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\IOFactory;

class LeerExcelService
{
    public function __invoke($request)
    {
        return $this->leer($request);
    }

    /*
        Leer datos de archivo Excel
        Input: request
        Output: array: [hoja => [fila => [columna => valor]]]
    */
    public function leer($request)
    {
        $data = [];

        // Validar que request tiene al menos un archivo
        if($request->allFiles()) {

            // Obtener data solo del primer archivo
            foreach( $request->file() as $file) {
                $archivo = $file;
                break;
            }

            if( !$archivo ) {
                return $data;
            }

            $spreadsheet = IOFactory::load($archivo);
            $hojas = $spreadsheet->getSheetCount();
            for ($i = 0; $i < $hojas; $i++) {
                $hoja = $spreadsheet->getSheet($i);
                $data[$i] = $hoja->toArray(null, true, true, true);
            }

        }

        return $data;
    }
}