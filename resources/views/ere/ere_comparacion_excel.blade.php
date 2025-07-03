<?php

use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/* Definir color de encabezado */
$header_color = "dae0e5";

$archivo = new Spreadsheet();

/* Configurar hojas de archivo de hojas */
$archivo->removeSheetByIndex(0);

$hoja1 = new Worksheet($archivo, "Parametros");
$archivo->addSheet($hoja1, 0);

$hoja2 = new Worksheet($archivo, "Comparacion");
$archivo->addSheet($hoja2, 1);

$hoja3 = new Worksheet($archivo, "Evaluacion 1");
$archivo->addSheet($hoja3, 2);

$hoja4 = new Worksheet($archivo, "Evaluacion 2");
$archivo->addSheet($hoja4, 3);

/**
 * FORMATEAR PARAMETROS
 */

$hoja1->mergeCells('A1:B1');
$hoja1->setCellValue('A1', 'COMPARACIÓN DE EVALUACIONES');

$hoja1->setCellValue('A5', 'EVALUACIÓN 1:')
    ->setCellValue('B5', $filtros->evaluacion);

$hoja1->setCellValue('A6', 'EVALUACIÓN 2:')
    ->setCellValue('B6', $filtros->evaluacion2);

$hoja1->setCellValue('A7', 'CURSO:')
    ->setCellValue('B7', $filtros->curso);

$hoja1->setCellValue('A8', 'GRADO:')
    ->setCellValue('B8', $filtros->grado);

$hoja1->setCellValue('A9', 'NIVEL:')
    ->setCellValue('B9', $filtros->nivel);

if( isset($filtros->cod_ie) )
    $hoja1->setCellValue('A10', 'I.E.:')
        ->setCellValue('B10', $filtros->cod_ie);

if( isset($filtros->ugel) ) {
    $hoja1->setCellValue('A11', 'UGEL:')
        ->setCellValue('B11', $filtros->ugel);
}

if( isset($filtros->distrito) ) {
    $hoja1->setCellValue('A12', 'DISTRITO:')
        ->setCellValue('B12', $filtros->distrito);
}

if( isset($filtros->seccion) ) {
    $hoja1->setCellValue('A13', 'SECCION:')
        ->setCellValue('B13', $filtros->seccion);
}

if( isset($filtros->sexo) ) {
    $hoja1->setCellValue('A14', 'SEXO:')
        ->setCellValue('B14', $filtros->sexo);
}

if( isset($filtros->sector) ) {
    $hoja1->setCellValue('A15', 'GESTIÓN:')
        ->setCellValue('B15', $filtros->sector);
}

if( isset($filtros->zona) ) {
    $hoja1->setCellValue('A16', 'ZONA:')
        ->setCellValue('B16', $filtros->zona);
}

$hoja1->getStyle('A1:A16')
    ->getFont()
    ->setBold(true);
$hoja1->getStyle('A1:A1')
    ->getFill()
    ->setFillType(Fill::FILL_SOLID)
    ->getStartColor()
    ->setARGB($header_color);
$hoja1->getStyle('A5:A16')
    ->getFill()
    ->setFillType(Fill::FILL_SOLID)
    ->getStartColor()
    ->setARGB($header_color);
$hoja1->getStyle('A1:B1')
    ->getAlignment()
    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
    ->setVertical(Alignment::VERTICAL_CENTER);
$hoja1->getStyle('A1:B1')
    ->getFont()
    ->setSize(14);

/**
 * FORMATEAR COMPARACION
 */

$hoja2->setCellValue('A1', 'NIVEL')
    ->setCellValue('B1', $filtros->evaluacion)
    ->setCellValue('D1', $filtros->evaluacion2)
    ->setCellValue('B2', 'CANTIDAD')
    ->setCellValue('C2', 'PORCENTAJE')
    ->setCellValue('D2', 'CANTIDAD')
    ->setCellValue('E2', 'PORCENTAJE');

$fila = 3;
foreach($niveles as $key => $nivel) {
    $hoja2->setCellValue('A'.$fila, $nivel['nivel'])
        ->setCellValue('B'.$fila, $nivel['cantidad1'])
        ->setCellValue('C'.$fila, $nivel['porcentaje1'])
        ->setCellValue('D'.$fila, $nivel['cantidad2'])
        ->setCellValue('E'.$fila, $nivel['porcentaje2']);
    $fila++;
}

$hoja2->setCellValue('A'.$fila, 'TOTAL DE ESTUDIANTES')
        ->setCellValue('B'.$fila, $total1)
        ->setCellValue('D'.$fila, $total2);

$hoja2->mergeCells('A1:A2');
$hoja2->mergeCells('B1:C1');
$hoja2->mergeCells('D1:E1');
$hoja2->getStyle('A1:E2')
    ->getFont()
    ->setBold(true);
$hoja2->getStyle('A1:E2')
    ->getFill()
    ->setFillType(Fill::FILL_SOLID)
    ->getStartColor()
    ->setARGB($header_color);
$hoja2->getStyle('A'.$fila.':E'.$fila)
    ->getFill()
    ->setFillType(Fill::FILL_SOLID)
    ->getStartColor()
    ->setARGB($header_color);
$hoja2->getStyle('A'.$fila.':E'.$fila)
    ->getFont()
    ->setBold(true);
$hoja2->getStyle('B:E')
    ->getAlignment()
    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
    ->setVertical(Alignment::VERTICAL_CENTER);

/**
 * FORMATEAR RESULTADOS EVALUACION 1
 */

$hoja3->setCellValue('A1', 'ITEM')
    ->setCellValue('B1', 'I.E.')
    ->setCellValue('C1', 'DISTRITO')
    ->setCellValue('D1', 'SECCION')
    ->setCellValue('E1', 'ESTUDIANTE')
    ->setCellValue('F1', 'ACIERTOS')
    ->setCellValue('G1', 'DESACIERTOS')
    ->setCellValue('H1', 'BLANCOS')
    ->setCellValue('J1', 'NIVEL DE LOGRO')
    ->setCellValue('I1', 'DOCENTE');
for ($pregunta = 1; $pregunta <= 20; $pregunta++) {
    $columnaLetra = Coordinate::stringFromColumnIndex(10 + $pregunta);
    $hoja3->setCellValue($columnaLetra . '1', $pregunta);
}

$hoja3->getStyle('A1:' . $columnaLetra . '1')
    ->getFont()
    ->setBold(true);
$hoja3->getStyle('A1:' . $columnaLetra . '1')
    ->getFill()
    ->setFillType(Fill::FILL_SOLID)
    ->getStartColor()
    ->setARGB($header_color);
$hoja3->getStyle('A1:' . $columnaLetra . count($resultados1) + 1)
    ->getAlignment()
    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
    ->setVertical(Alignment::VERTICAL_CENTER);
$hoja3->getStyle('B:B')
    ->getAlignment()
    ->setHorizontal(Alignment::HORIZONTAL_LEFT)
    ->setWrapText(true);
$hoja3->getStyle('E:E')
    ->getAlignment()
    ->setHorizontal(Alignment::HORIZONTAL_LEFT)
    ->setWrapText(true);
$hoja3->getStyle('I:I')
    ->getAlignment()
    ->setHorizontal(Alignment::HORIZONTAL_LEFT)
    ->setWrapText(true);

foreach( $resultados1 as $key => $value ) {
    $hoja3->setCellValue('A' . ($key + 2), $key + 1)
        ->setCellValue('B' . ($key + 2), $value->cod_ie)
        ->setCellValue('C' . ($key + 2), $value->distrito)
        ->setCellValue('D' . ($key + 2), $value->seccion)
        ->setCellValue('E' . ($key + 2), $value->estudiante)
        ->setCellValue('F' . ($key + 2), $value->aciertos)
        ->setCellValue('G' . ($key + 2), $value->desaciertos)
        ->setCellValue('H' . ($key + 2), $value->blancos)
        ->setCellValue('J' . ($key + 2), $value->nivel_logro)
        ->setCellValue('I' . ($key + 2), $value->docente);

    $indice = 1;

    foreach ($value->respuestas as $value) {
        $columnaLetra = Coordinate::stringFromColumnIndex(10 + $indice);
        $hoja3->setCellValue($columnaLetra . ($key + 2), $value->r);
        $color = $value->c == true ? Color::COLOR_DARKGREEN : Color::COLOR_RED;
        $relleno = $value->c == true ? Fill::FILL_SOLID : Fill::FILL_NONE;
        $fondo = $value->c == true ? $header_color : Color::COLOR_WHITE;
        $hoja3->getStyle($columnaLetra . ($key + 2))->getFont()->getColor()->setARGB($color);
        $hoja3->getStyle($columnaLetra . ($key + 2))->getFill()->setFillType($relleno)->getStartColor()->setARGB($fondo);
        $indice++;
    }
}

/**
 * FORMATEAR RESULTADOS EVALUACION 2
 */

$hoja4->setCellValue('A1', 'ITEM')
    ->setCellValue('B1', 'I.E.')
    ->setCellValue('C1', 'DISTRITO')
    ->setCellValue('D1', 'SECCION')
    ->setCellValue('E1', 'ESTUDIANTE')
    ->setCellValue('F1', 'ACIERTOS')
    ->setCellValue('G1', 'DESACIERTOS')
    ->setCellValue('H1', 'BLANCOS')
    ->setCellValue('J1', 'NIVEL DE LOGRO')
    ->setCellValue('I1', 'DOCENTE');
for ($pregunta = 1; $pregunta <= 20; $pregunta++) {
    $columnaLetra = Coordinate::stringFromColumnIndex(10 + $pregunta);
    $hoja4->setCellValue($columnaLetra . '1', $pregunta);
}

$hoja4->getStyle('A1:' . $columnaLetra . '1')
    ->getFont()
    ->setBold(true);
$hoja4->getStyle('A1:' . $columnaLetra . '1')
    ->getFill()
    ->setFillType(Fill::FILL_SOLID)
    ->getStartColor()
    ->setARGB($header_color);
$hoja4->getStyle('A1:' . $columnaLetra . count($resultados2) + 1)
    ->getAlignment()
    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
    ->setVertical(Alignment::VERTICAL_CENTER);
$hoja4->getStyle('B:B')
    ->getAlignment()
    ->setHorizontal(Alignment::HORIZONTAL_LEFT)
    ->setWrapText(true);
$hoja4->getStyle('E:E')
    ->getAlignment()
    ->setHorizontal(Alignment::HORIZONTAL_LEFT)
    ->setWrapText(true);
$hoja4->getStyle('I:I')
    ->getAlignment()
    ->setHorizontal(Alignment::HORIZONTAL_LEFT)
    ->setWrapText(true);

foreach( $resultados2 as $key => $value ) {
    $hoja4->setCellValue('A' . ($key + 2), $key + 1)
        ->setCellValue('B' . ($key + 2), $value->cod_ie)
        ->setCellValue('C' . ($key + 2), $value->distrito)
        ->setCellValue('D' . ($key + 2), $value->seccion)
        ->setCellValue('E' . ($key + 2), $value->estudiante)
        ->setCellValue('F' . ($key + 2), $value->aciertos)
        ->setCellValue('G' . ($key + 2), $value->desaciertos)
        ->setCellValue('H' . ($key + 2), $value->blancos)
        ->setCellValue('J' . ($key + 2), $value->nivel_logro)
        ->setCellValue('I' . ($key + 2), $value->docente);

    $indice = 1;

    foreach ($value->respuestas as $value) {
        $columnaLetra = Coordinate::stringFromColumnIndex(10 + $indice);
        $hoja4->setCellValue($columnaLetra . ($key + 2), $value->r);
        $color = $value->c == true ? Color::COLOR_DARKGREEN : Color::COLOR_RED;
        $relleno = $value->c == true ? Fill::FILL_SOLID : Fill::FILL_NONE;
        $fondo = $value->c == true ? $header_color : Color::COLOR_WHITE;
        $hoja4->getStyle($columnaLetra . ($key + 2))->getFont()->getColor()->setARGB($color);
        $hoja4->getStyle($columnaLetra . ($key + 2))->getFill()->setFillType($relleno)->getStartColor()->setARGB($fondo);
        $indice++;
    }
}

$hojas = [$hoja1, $hoja2, $hoja3, $hoja4];

/**
 * CONFIGURAR DIMENSIONES DE CELDAS EN TODAS LAS HOJAS
 */

foreach ($hojas as $hoja)
{
    foreach ($hoja->getColumnIterator() as $columna)
    {
        $hoja->getColumnDimension($columna->getColumnIndex())->setAutoSize(true);
    }
}

/**
 * MOSTRAR DATOS GENERADOS
 */

$archivo->setActiveSheetIndex(0);

// $writer = new PhpOffice\PhpSpreadsheet\Writer\Html($archivo);
// $writer->save('php://output');

$writer = new PhpOffice\PhpSpreadsheet\Writer\Xlsx($archivo);
$writer->save('php://output');

ob_clean();
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="'. urlencode('comparacion-ere.xlsx') .'"');
$writer->save('php://output');

?>