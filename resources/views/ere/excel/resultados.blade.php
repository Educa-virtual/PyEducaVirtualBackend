<?php

use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

$archivo = new Spreadsheet();

$archivo->removeSheetByIndex(0);

$hoja1 = new Worksheet($archivo, "Resultados");
$archivo->addSheet($hoja1, 0);

$hoja2 = new Worksheet($archivo, "Resumen");
$archivo->addSheet($hoja2, 1);

$hoja3 = new Worksheet($archivo, "Matriz");
$archivo->addSheet($hoja3, 2);

$hoja1_datos = $resultados;
$hoja2_datos = $resumen;
$hoja3_datos = $matriz;

// $hoja1->fromArray($hoja1_datos);

$hoja1->setCellValue('A1', 'ITEM')
    ->setCellValue('B1', 'I.E.')
    ->setCellValue('C1', 'DISTRITO')
    ->setCellValue('D1', 'SECCION')
    ->setCellValue('E1', 'ESTUDIANTE')
    ->setCellValue('F1', 'ACIERTOS')
    ->setCellValue('G1', 'DESACIERTOS')
    ->setCellValue('H1', 'BLANCOS')
    ->setCellValue('I1', 'DOCENTE')
    ->setCellValue('J1', 'NIVEL DE LOGRO');
    foreach (json_decode($resultados[0]->respuestas) as $key => $value) {
        $columnaLetra = Coordinate::stringFromColumnIndex(11 + $key);
        $hoja1->setCellValue($columnaLetra . '1', $key + 1);
    }

foreach( $resultados as $key => $value ) {
    $hoja1->setCellValue('A' . ($key + 2), $key + 1)
        ->setCellValue('B' . ($key + 2), $value->cod_ie)
        ->setCellValue('C' . ($key + 2), $value->distrito)
        ->setCellValue('D' . ($key + 2), $value->seccion)
        ->setCellValue('E' . ($key + 2), $value->estudiante)
        ->setCellValue('F' . ($key + 2), $value->aciertos)
        ->setCellValue('G' . ($key + 2), $value->desaciertos)
        ->setCellValue('H' . ($key + 2), $value->blancos)
        ->setCellValue('I' . ($key + 2), $value->docente)
        ->setCellValue('J' . ($key + 2), $value->nivel_logro);

    $indice = 0;

    foreach (json_decode($value->respuestas) as $value) {
        $columnaLetra = Coordinate::stringFromColumnIndex(11 + $indice);
        $hoja1->setCellValue($columnaLetra . ($key + 2), $value->respuesta);
        $color = $value->correcta == true ? Color::COLOR_GREEN : Color::COLOR_RED;
        $hoja1->getStyle($columnaLetra . ($key + 2))->getFont()->getColor()->setARGB($color);
        $indice++;
    }
}

$hoja2->fromArray($hoja2_datos);
$hoja3->fromArray($hoja3_datos);

$hojas = [$hoja1, $hoja2, $hoja3];

foreach ($hojas as $hoja)
{
    foreach ($hoja->getColumnIterator() as $columna)
    {
        $hoja->getColumnDimension($columna->getColumnIndex())->setAutoSize(true);
    }
}

$writer = new PhpOffice\PhpSpreadsheet\Writer\Xlsx($archivo);

ob_clean();
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="'. urlencode('resultados-ere.xlsx') .'"');
$writer->save('php://output');
?>