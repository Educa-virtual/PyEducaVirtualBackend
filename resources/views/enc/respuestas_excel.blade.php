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

$hoja2 = new Worksheet($archivo, "Preguntas");
$archivo->addSheet($hoja2, 1);

$hoja3 = new Worksheet($archivo, "Respuestas");
$archivo->addSheet($hoja3, 2);

/**
 * FORMATEAR PARAMETROS
 */

$hoja1->mergeCells('A1:B1');
$hoja1->setCellValue('A1', 'DATOS DE LA ENCUESTA');

$hoja1->setCellValue('A5', 'FECHA DE INICIO:')
    ->setCellValue('B5', $encuesta->dEncuInicio);

$hoja1->setCellValue('A6', 'FECHA DE CIERRE:')
    ->setCellValue('B6', $encuesta->dEncuFin);

$hoja1->setCellValue('A7', 'CATEGORÍA:')
    ->setCellValue('B7', $encuesta->cCateNombre);

$hoja1->setCellValue('A8', 'NOMBRE:')
    ->setCellValue('B8', $encuesta->cEncuNombre);

$hoja1->setCellValue('A9', 'SUBTITULO:')
    ->setCellValue('B9', $encuesta->cEncuSubtitulo);

$hoja1->setCellValue('A10', 'DESCRIPCIÓN:')
    ->setCellValue('B10', $encuesta->cEncuDescripcion);

$hoja1->setCellValue('A11', 'CREADA POR:')
    ->setCellValue('B11', $encuesta->cCreador);

$hoja1->mergeCells('A12:B12');
$hoja1->setCellValue('A12', 'FILTROS APLICADOS');

$pos_inicial = 12;

if($filtros->persona) {
    $pos_inicial++;
    $hoja1->setCellValue('A'.$pos_inicial, 'PERSONA:')
        ->setCellValue('B'.$pos_inicial, $filtros->persona);
}

if($filtros->nivel_tipo) {
    $pos_inicial++;
    $hoja1->setCellValue('A'.$pos_inicial, 'NIVEL EDUCATIVO:')
        ->setCellValue('B'.$pos_inicial, $filtros->nivel_tipo);
}

if($filtros->tipo_sector) {
    $pos_inicial++;
    $hoja1->setCellValue('A'.$pos_inicial, 'TIPO DE SECTOR:')
        ->setCellValue('B'.$pos_inicial, $filtros->tipo_sector);
}

if($filtros->zona) {
    $pos_inicial++;
    $hoja1->setCellValue('A'.$pos_inicial, 'ZONA:')
        ->setCellValue('B'.$pos_inicial, $filtros->zona);
}

if($filtros->ugel) {
    $pos_inicial++;
    $hoja1->setCellValue('A'.$pos_inicial, 'UGEL:')
        ->setCellValue('B'.$pos_inicial, $filtros->ugel);
}

if($filtros->distrito) {
    $pos_inicial++;
    $hoja1->setCellValue('A'.$pos_inicial, 'DISTRITO:')
        ->setCellValue('B'.$pos_inicial, $filtros->distrito);
}

if($filtros->ie) {
    $pos_inicial++;
    $hoja1->setCellValue('A'.$pos_inicial, 'I.E.:')
        ->setCellValue('B'.$pos_inicial, $filtros->ie);
}

if($filtros->grado) {
    $pos_inicial++;
    $hoja1->setCellValue('A'.$pos_inicial, 'GRADO:')
        ->setCellValue('B'.$pos_inicial, $filtros->grado);
}

if($filtros->seccion) {
    $pos_inicial++;
    $hoja1->setCellValue('A'.$pos_inicial, 'SECCION:')
        ->setCellValue('B'.$pos_inicial, $filtros->seccion);
}

if($filtros->curso) {
    $pos_inicial++;
    $hoja1->setCellValue('A'.$pos_inicial, 'CURSO:')
        ->setCellValue('B'.$pos_inicial, $filtros->curso);
}

if($filtros->genero) {
    $pos_inicial++;
    $hoja1->setCellValue('A'.$pos_inicial, 'GENERO:')
        ->setCellValue('B'.$pos_inicial, $filtros->genero);
}

$hoja1->getStyle('A1:A'.$pos_inicial)
    ->getFont()
    ->setBold(true);
$hoja1->getStyle('A5:A'.$pos_inicial)
    ->getFill()
    ->setFillType(Fill::FILL_SOLID)
    ->getStartColor()
    ->setARGB($header_color);
$hoja1->getStyle('A1:B1')
    ->getFill()
    ->setFillType(Fill::FILL_SOLID)
    ->getStartColor()
    ->setARGB($header_color);
$hoja1->getStyle('A12:B12')
    ->getFill()
    ->setFillType(Fill::FILL_SOLID)
    ->getStartColor()
    ->setARGB($header_color);
$hoja1->getStyle('A12:B12')
    ->getAlignment()
    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
    ->setVertical(Alignment::VERTICAL_CENTER);
$hoja1->getStyle('A1:B1')
    ->getAlignment()
    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
    ->setVertical(Alignment::VERTICAL_CENTER);
$hoja1->getStyle('A1:B1')
    ->getFont()
    ->setSize(14);
$hoja1->getStyle('A12:B12')
    ->getFont()
    ->setSize(14);

/**
 * FORMATEAR PREGUNTAS
 */

$hoja2->setCellValue('A1', 'NRO')
    ->setCellValue('B1', 'SECCIÓN')
    ->setCellValue('C1', 'TIPO')
    ->setCellValue('D1', 'PREGUNTA')
    ->setCellValue('E1', 'INFO ADICIONAL')
    ->setCellValue('F1', 'ALTERNATIVAS')
    ;

$hoja2->getStyle('A1:F1')
    ->getFont()
    ->setBold(true);
$hoja2->getStyle('A1:F1')
    ->getFill()
    ->setFillType(Fill::FILL_SOLID)
    ->getStartColor()
    ->setARGB($header_color);
$hoja2->getStyle('A1:F1')
    ->getAlignment()
    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
    ->setVertical(Alignment::VERTICAL_CENTER);
$hoja2->getStyle('C:F')
    ->getAlignment()
    ->setHorizontal(Alignment::HORIZONTAL_LEFT)
    ->setWrapText(true);

foreach( $preguntas as $key => $pregunta ) {
    $hoja2->setCellValue('A' . ($key + 2), $key + 1)
        ->setCellValue('B' . ($key + 2), $pregunta?->cSeccionTitulo)
        ->setCellValue('C' . ($key + 2), $pregunta?->cTipoPregNombre)
        ->setCellValue('D' . ($key + 2), $pregunta?->cPregContenido)
        ->setCellValue('E' . ($key + 2), $pregunta?->cPregAdicional)
        ->setCellValue('F' . ($key + 2), $pregunta?->alternativas);
}

/**
 * FORMATEAR RESPUESTAS
 */

$hoja3->setCellValue('A1', 'ITEM')
    ->setCellValue('B1', 'CODIGO MODULAR')
    ->setCellValue('C1', 'I.E.')
    ->setCellValue('D1', 'GRADO')
    ->setCellValue('E1', 'SECCION')
    ->setCellValue('F1', 'PERSONA');

foreach($preguntas as $key => $pregunta) {
    $columnaLetra = Coordinate::stringFromColumnIndex(7 + $key);
    $hoja3->setCellValue($columnaLetra . '1', $pregunta?->cPregContenido);
}

$hoja3->getStyle('A1:' . $columnaLetra . '1')
    ->getFont()
    ->setBold(true);
$hoja3->getStyle('A1:' . $columnaLetra . '1')
    ->getFill()
    ->setFillType(Fill::FILL_SOLID)
    ->getStartColor()
    ->setARGB($header_color);
$hoja3->getStyle('A1:F1')
    ->getAlignment()
    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
    ->setVertical(Alignment::VERTICAL_CENTER);
$hoja3->getStyle('F:F')
    ->getAlignment()
    ->setHorizontal(Alignment::HORIZONTAL_LEFT)
    ->setWrapText(true);

foreach( $respuestas as $key => $respuesta ) {
    $hoja3->setCellValue('A' . ($key + 2), $key + 1)
        ->setCellValue('B' . ($key + 2), $respuesta->cIieeCodigoModular)
        ->setCellValue('C' . ($key + 2), $respuesta->cIieeNombre)
        ->setCellValue('D' . ($key + 2), $respuesta->cGradoNombre)
        ->setCellValue('E' . ($key + 2), $respuesta->cSeccionNombre)
        ->setCellValue('F' . ($key + 2), $respuesta->cPersNombreApellidos);

    $indice = 1;

    foreach ($respuesta->respuestas as $value) {
        $columnaLetra = Coordinate::stringFromColumnIndex(6 + $indice);
        $hoja3->setCellValue($columnaLetra . ($key + 2), $value->cRespContenido);
        $indice++;
    }
}

/**
 * CONFIGURAR DIMENSIONES DE CELDAS EN TODAS LAS HOJAS
 */

$hojas = [$hoja1, $hoja2, $hoja3];

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
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="'. urlencode('respuestas-encuesta.xlsx') .'"');
$writer->save('php://output');

?>