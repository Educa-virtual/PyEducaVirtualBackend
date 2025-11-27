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
    ->setCellValue('B5', $filtros->ie_codigo);

$hoja1->setCellValue('A6', 'NOMBRE:')
    ->setCellValue('B6', $filtros->ie_nombre);

$hoja1->setCellValue('A7', 'NIVEL:')
    ->setCellValue('B7', $filtros->nivel_tipo);

$hoja1->setCellValue('A8', 'AÑO ACADÉMICO:')
    ->setCellValue('B8', $filtros->anio_academico);

$hoja1->setCellValue('A9', 'GRADO:')
    ->setCellValue('B9', $filtros->grado);

$hoja1->setCellValue('A10', 'SECCIÓN:')
    ->setCellValue('B10', $filtros->seccion);

$hoja1->setCellValue('A11', 'CANTIDAD DE ESTUDIANTES:')
    ->setCellValue('B11', $filtros->cantidad_estudiantes);

$hoja1->getStyle('A1:A11')
    ->getFont()
    ->setBold(true);
$hoja1->getStyle('A5:A11')
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

foreach($periodos as $index_periodo => $periodo) {
    $hoja_temp = new Worksheet($archivo, $periodo->cPeriodoNombre);
    $archivo->addSheet($hoja_temp, $index_periodo + 1);
    $hojas[] = $hoja_temp;

    $hoja_temp->setCellValue('A1', 'Orden')
        ->setCellValue('B1', 'Cód. Estudiante')
        ->setCellValue('C1', 'Apellidos y nombres');

    $hoja_temp->mergeCells('A1:A2');
    $hoja_temp->mergeCells('B1:B2');
    $hoja_temp->mergeCells('C1:C2');

    $columna_actual = Coordinate::columnIndexFromString('C');

    foreach ($competencias as $index => $competencia) {
        $columna_actual++;
        $columnaNivel = Coordinate::stringFromColumnIndex($columna_actual);
        $hoja_temp->setCellValue($columnaNivel . '2', 'NL');

        $columna_actual++;
        $columnaConclusion = Coordinate::stringFromColumnIndex($columna_actual);
        $hoja_temp->setCellValue($columnaConclusion . '2', 'Conclusión descriptiva de la competencia');
        $hoja_temp->getStyle($columnaConclusion.':'.$columnaConclusion)
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_LEFT)
            ->setWrapText(true);

        $hoja_temp->mergeCells($columnaNivel.'1:'.$columnaConclusion.'1');
        $hoja_temp->setCellValue($columnaNivel . '1', $competencia->iCompetenciaOrden);

        $hoja_temp->getStyle('A1:' . $columnaConclusion . '2')
            ->getFont()
            ->setBold(true);
        $hoja_temp->getStyle('A1:' . $columnaConclusion . '2')
            ->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB($header_color);
        $hoja_temp->getStyle('A1:' . $columnaConclusion . '2')
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER);
    }

    foreach ($notas as $index_nota => $nota) {
        $hoja_temp->setCellValueExplicit('A' . ($index_nota + 3), $index_nota + 2, DataType::TYPE_NUMERIC );
        $hoja_temp->setCellValueExplicit('B' . ($index_nota + 3), $nota->cEstCodigo, DataType::TYPE_STRING);
        $hoja_temp->setCellValueExplicit('C' . ($index_nota + 3), $nota->cPersNombreCompleto, DataType::TYPE_STRING);

        $columna_actual = Coordinate::columnIndexFromString('C');

        foreach ($nota->competencias as $index_competencia => $competencia) {
            $columna_actual++;
            $columnaNivel = Coordinate::stringFromColumnIndex($columna_actual);
            $hoja_temp->setCellValueExplicit($columnaNivel . ($index_nota + 3), $competencia->periodos[$index_periodo]->cNivelLogro, DataType::TYPE_STRING);

            $columna_actual++;
            $columnaConclusion = Coordinate::stringFromColumnIndex($columna_actual);
            $hoja_temp->setCellValueExplicit($columnaConclusion . ($index_nota + 3), $competencia->periodos[$index_periodo]->cDescripcion, DataType::TYPE_STRING);
        }
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

// $writer->save('hola2.xlsx');
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="'. urlencode('registro-notas.xlsx') .'"');

$writer->save('php://output');
