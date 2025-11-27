<?php

use Illuminate\Support\Facades\Log;
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
$hojas = [];

/* Configurar hojas de archivo de hojas */
$archivo->removeSheetByIndex(0);

$hoja1 = new Worksheet($archivo, "Parametros");
$archivo->addSheet($hoja1, 0);
$hojas[] = $hoja1;


/**
 * FORMATEAR PARAMETROS
 */

$hoja1->mergeCells('A1:B1');
$hoja1->setCellValue('A1', 'REGISTRO DE NOTAS');

$hoja1->setCellValue('A5', 'CÓDIGO MODULAR:')
    ->setCellValueExplicit('B5', $filtros->ie_codigo, DataType::TYPE_STRING);

$hoja1->setCellValue('A6', 'NOMBRE:')
    ->setCellValueExplicit('B6', $filtros->ie_nombre, DataType::TYPE_STRING);

$hoja1->setCellValue('A7', 'NIVEL:')
    ->setCellValueExplicit('B7', $filtros->nivel_tipo, DataType::TYPE_STRING);

$hoja1->setCellValue('A8', 'AÑO ACADÉMICO:')
    ->setCellValueExplicit('B8', $filtros->anio_academico, DataType::TYPE_STRING);

$hoja1->setCellValue('A9', 'GRADO:')
    ->setCellValueExplicit('B9', $filtros->grado, DataType::TYPE_STRING);

$hoja1->setCellValue('A10', 'SECCIÓN:')
    ->setCellValueExplicit('B10', $filtros->seccion, DataType::TYPE_STRING);

$hoja1->setCellValue('A11', 'CANTIDAD DE ESTUDIANTES:')
    ->setCellValueExplicit('B11', $filtros->cantidad_estudiantes, DataType::TYPE_STRING);

$hoja1->setCellValue('A12', 'PERIODO:')
    ->setCellValueExplicit('B12', $filtros->periodo, DataType::TYPE_STRING);

$hoja1->getStyle('A1:A12')
    ->getFont()
    ->setBold(true);
$hoja1->getStyle('A5:A12')
    ->getFill()
    ->setFillType(Fill::FILL_SOLID)
    ->getStartColor()
    ->setARGB($header_color);
$hoja1->getStyle('A1:B1')
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

$hoja1->mergeCells('A15:B15');
$hoja1->setCellValue('A15', 'COMPETENCIAS:');
$fila_actual = 15;

foreach($competencias as $index => $competencia) {
    $fila_actual++;
    $hoja1->setCellValue('A' . $fila_actual, $competencia->iCompetenciaOrden);
    $hoja1->setCellValue('B' . $fila_actual, $competencia->cCompetenciaNombre);
}

$hoja1->getStyle('A15:A' . $fila_actual)
    ->getFont()
    ->setBold(true);
$hoja1->getStyle('A15:A' . $fila_actual)
    ->getFill()
    ->setFillType(Fill::FILL_SOLID)
    ->getStartColor()
    ->setARGB($header_color);
$hoja1->getStyle('A15:B15')
    ->getFill()
    ->setFillType(Fill::FILL_SOLID)
    ->getStartColor()
    ->setARGB($header_color);
$hoja1->getStyle('A15:B15')
    ->getAlignment()
    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
    ->setVertical(Alignment::VERTICAL_CENTER);
$hoja1->getStyle('A15:B15')
    ->getFont()
    ->setSize(14);

$hoja2 = new Worksheet($archivo, $filtros->periodo);
$archivo->addSheet($hoja2, 1);
$hojas[] = $hoja2;

$hoja2->setCellValue('A1', 'Orden')
    ->setCellValue('B1', 'Cód. Estudiante')
    ->setCellValue('C1', 'Apellidos y nombres');

$hoja2->mergeCells('A1:A2');
$hoja2->mergeCells('B1:B2');
$hoja2->mergeCells('C1:C2');

$columna_actual = Coordinate::columnIndexFromString('C');

foreach ($competencias as $index => $competencia) {
    $columna_actual++;
    $columnaNivel = Coordinate::stringFromColumnIndex($columna_actual);
    $hoja2->setCellValue($columnaNivel . '2', 'NL');

    $columna_actual++;
    $columnaConclusion = Coordinate::stringFromColumnIndex($columna_actual);
    $hoja2->setCellValue($columnaConclusion . '2', 'Conclusión descriptiva de la competencia');
    $hoja2->getStyle($columnaConclusion.':'.$columnaConclusion)
        ->getAlignment()
        ->setHorizontal(Alignment::HORIZONTAL_LEFT)
        ->setWrapText(true);

    $hoja2->mergeCells($columnaNivel.'1:'.$columnaConclusion.'1');
    $hoja2->setCellValue($columnaNivel . '1', $competencia->iCompetenciaOrden);

    $hoja2->getStyle('A1:' . $columnaConclusion . '2')
        ->getFont()
        ->setBold(true);
    $hoja2->getStyle('A1:' . $columnaConclusion . '2')
        ->getFill()
        ->setFillType(Fill::FILL_SOLID)
        ->getStartColor()
        ->setARGB($header_color);
    $hoja2->getStyle('A1:' . $columnaConclusion . '2')
        ->getAlignment()
        ->setHorizontal(Alignment::HORIZONTAL_CENTER)
        ->setVertical(Alignment::VERTICAL_CENTER);
}

foreach ($notas as $index_nota => $nota) {
    $hoja2->setCellValueExplicit('A' . ($index_nota + 3), $index_nota + 2, DataType::TYPE_NUMERIC);
    $hoja2->setCellValueExplicit('B' . ($index_nota + 3), $nota->cEstCodigo, DataType::TYPE_STRING);
    $hoja2->setCellValueExplicit('C' . ($index_nota + 3), $nota->cPersNombreCompleto, DataType::TYPE_STRING);

    $columna_actual = Coordinate::columnIndexFromString('C');

    foreach ($nota->competencias as $index_competencia => $competencia) {
        $columna_actual++;
        $columnaNivel = Coordinate::stringFromColumnIndex($columna_actual);
        $hoja2->setCellValueExplicit($columnaNivel . ($index_nota + 3), $competencia->cNivelLogro, DataType::TYPE_STRING);

        $columna_actual++;
        $columnaConclusion = Coordinate::stringFromColumnIndex($columna_actual);
        $hoja2->setCellValueExplicit($columnaConclusion . ($index_nota + 3), $competencia->cDescripcion, DataType::TYPE_STRING);
    }
}

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


ob_clean();
$writer = new PhpOffice\PhpSpreadsheet\Writer\Xlsx($archivo);

// $writer->save('hola3.xlsx');
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="'. urlencode('registro-notas.xlsx') .'"');

$writer->save('php://output');
