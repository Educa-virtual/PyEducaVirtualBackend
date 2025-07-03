<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class ConvertirPdfCsv
{
    public function __invoke($request, $params = [])
    {
        return $this->convertir($request, $params = []);
    }

    /*
        Convertir archivo pdf a csv (comma separated values)
        Input: request (con al menos un archivo)
        Output: array con el nombre del archivo original y el csv
    */
    public function convertir($request, $params = [])
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

            try {
                $nombre_archivo = $archivo->getClientOriginalName();
                $ruta_archivo = $archivo->getRealPath();

                // Almacenar archivo de forma temporal
                Storage::disk('local')->put($nombre_archivo, $archivo);

                if($params['lattice']) {
                    $lattice = "-l";
                } else {
                    $lattice = "";
                }

                if($params['array_coordenadas'] ) {
                    $percentage = $params['percentage_coordenadas'] ? "%" : "";
                    $top = $params['top'] ?? "0";
                    $left = $params['left'] ?? "0";
                    $bottom = $params['bottom'] ?? "100";
                    $right = $params['right'] ?? "100";
                    $coordenadas = "-a $percentage$top $left $bottom $right";
                }
                if($params['array_paginas']) {
                    $paginas = "-p " . implode(',', $params['array_paginas']);
                } else {
                    $paginas = "-p all";
                }

                if( !Storage::disk('local')->exists($nombre_archivo) ) {
                    return $data;
                }

                /*
                    java -jar tabula-1.0.5-jar-with-dependencies.jar -f CSV -l -a %0,0,100,100 -t achivo.pdf -o salida.csv
                    Opciones para TabulaPDF:
                        -a,--area <AREA> (top,left,bottom,right), prefix % for percentages, default is entire page.
                        -c,--columns <COLUMNS> X coordinates of column boundaries.
                        -d,--debug                 Print detected table areas instead of processing.
                        -f,--format <FORMAT>       Output format: (CSV,TSV,JSON). Default: CSV
                        -g,--guess                 Guess the portion of the page to analyze per page.
                        -i,--silent                Suppress all stderr output.
                        -l,--lattice               Force lattice-mode extraction (PDF of Excel).
                        -o,--outfile <OUTFILE>     Write output to <file> instead of STDOUT.
                        -p,--pages <PAGES>         Comma separated list of ranges, or all. Default is -p 1.
                        -s,--password <PASSWORD>   Password to decrypt document. Default is empty.
                        -t,--stream                Force PDF to be extracted using stream-mode extraction
                */

                $csv = exec("java -jar tabula-1.0.5-jar-with-dependencies.jar -f CSV $lattice $coordenadas $paginas -t $nombre_archivo" );
                Storage::disk('local')->delete($nombre_archivo);
            } catch (\Exception $e) {
                return $data;
                Storage::exists($nombre_archivo);
                Storage::disk('local')->delete($nombre_archivo);
            }

            $data[] = [
                'nombre_archivo' => $nombre_archivo,
                'csv' => $csv,
            ];
        }

        return $data;
    }
}