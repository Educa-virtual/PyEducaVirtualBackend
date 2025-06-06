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

$hoja2 = new Worksheet($archivo, "Detalle");
$archivo->addSheet($hoja2, 1);

$hoja3 = new Worksheet($archivo, "Preguntas");
$archivo->addSheet($hoja3, 2);

$hoja4 = new Worksheet($archivo, "Matriz");
$archivo->addSheet($hoja4, 3);

if( $filtros->tipo_reporte == 'IE' ) {
    $hoja5 = new Worksheet($archivo, "Agrupado");
    $archivo->addSheet($hoja5, 4);
}

$hoja2_datos = $resultados;
$hoja3_datos = $resumen;
$hoja4_datos = $matriz;

/**
 * FORMATEAR PARAMETROS
 */

$hoja1->mergeCells('A1:B1');
$hoja1->setCellValue('A1', 'RESULTADOS DE EVALUACIÓN');

$hoja1->setCellValue('A5', 'EVALUACIÓN:')
    ->setCellValue('B5', $filtros->evaluacion);

$hoja1->setCellValue('A6', 'CURSO:')
    ->setCellValue('B6', $filtros->curso);

$hoja1->setCellValue('A7', 'GRADO:')
    ->setCellValue('B7', $filtros->grado);

$hoja1->setCellValue('A8', 'NIVEL:')
    ->setCellValue('B8', $filtros->nivel);

if( isset($filtros->cod_ie) )
    $hoja1->setCellValue('A9', 'I.E.:')
        ->setCellValue('B9', $filtros->cod_ie);

if( isset($filtros->ugel) ) {
    $hoja1->setCellValue('A10', 'UGEL:')
        ->setCellValue('B10', $filtros->ugel);
}

if( isset($filtros->distrito) ) {
    $hoja1->setCellValue('A11', 'DISTRITO:')
        ->setCellValue('B11', $filtros->distrito);
}

if( isset($filtros->seccion) ) {
    $hoja1->setCellValue('A12', 'SECCION:')
        ->setCellValue('B12', $filtros->seccion);
}

if( isset($filtros->sexo) ) {
    $hoja1->setCellValue('A13', 'SEXO:')
        ->setCellValue('B13', $filtros->sexo);
}

if( isset($filtros->sector) ) {
    $hoja1->setCellValue('A14', 'GESTIÓN:')
        ->setCellValue('B14', $filtros->sector);
}

if( isset($filtros->zona) ) {
    $hoja1->setCellValue('A15', 'ZONA:')
        ->setCellValue('B15', $filtros->zona);
}

$hoja1->mergeCells('A16:B16');
$hoja1->setCellValue('A16', 'NIVELES DE LOGRO');

$fila = 16;
$count_estudiantes = 0;

foreach($niveles as $nivel) {
    $fila++;
    $hoja1->setCellValue('A' . $fila, $nivel->nivel_logro . ':')
        ->setCellValue('B' . $fila, $nivel->cantidad);
    $count_estudiantes += $nivel->cantidad;
}
$hoja1->setCellValue('A' . ($fila+1), 'TOTAL DE ESTUDIANTES:')
    ->setCellValue('B' . ($fila+1), $count_estudiantes);

$hoja1->getStyle('A1:A'.$fila+1)
    ->getFont()
    ->setBold(true);
$hoja1->getStyle('A16:B16')
    ->getFill()
    ->setFillType(Fill::FILL_SOLID)
    ->getStartColor()
    ->setARGB($header_color);
$hoja1->getStyle('A'.($fila+1).':B'.($fila+1))
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

/**
 * FORMATEAR RESULTADOS
 */

$hoja2->setCellValue('A1', 'ITEM')
    ->setCellValue('B1', 'I.E.')
    ->setCellValue('C1', 'DISTRITO')
    ->setCellValue('D1', 'SECCION')
    ->setCellValue('E1', 'ESTUDIANTE')
    ->setCellValue('F1', 'ACIERTOS')
    ->setCellValue('G1', 'DESACIERTOS')
    ->setCellValue('H1', 'BLANCOS')
    ->setCellValue('J1', 'NIVEL DE LOGRO')
    ->setCellValue('I1', 'DOCENTE');
for ($pregunta = 1; $pregunta <= $nro_preguntas; $pregunta++) {
    $columnaLetra = Coordinate::stringFromColumnIndex(10 + $pregunta);
    $hoja2->setCellValue($columnaLetra . '1', $pregunta);
}

$hoja2->getStyle('A1:' . $columnaLetra . '1')
    ->getFont()
    ->setBold(true);
$hoja2->getStyle('A1:' . $columnaLetra . '1')
    ->getFill()
    ->setFillType(Fill::FILL_SOLID)
    ->getStartColor()
    ->setARGB($header_color);
$hoja2->getStyle('A1:' . $columnaLetra . count($resultados) + 1)
    ->getAlignment()
    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
    ->setVertical(Alignment::VERTICAL_CENTER);
$hoja2->getStyle('B:B')
    ->getAlignment()
    ->setHorizontal(Alignment::HORIZONTAL_LEFT)
    ->setWrapText(true);
$hoja2->getStyle('E:E')
    ->getAlignment()
    ->setHorizontal(Alignment::HORIZONTAL_LEFT)
    ->setWrapText(true);
$hoja2->getStyle('I:I')
    ->getAlignment()
    ->setHorizontal(Alignment::HORIZONTAL_LEFT)
    ->setWrapText(true);

foreach( $resultados as $key => $value ) {
    $hoja2->setCellValue('A' . ($key + 2), $key + 1)
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
        $hoja2->setCellValue($columnaLetra . ($key + 2), $value->r);
        $color = $value->c == true ? Color::COLOR_DARKGREEN : Color::COLOR_RED;
        $relleno = $value->c == true ? Fill::FILL_SOLID : Fill::FILL_NONE;
        $fondo = $value->c == true ? $header_color : Color::COLOR_WHITE;
        $hoja2->getStyle($columnaLetra . ($key + 2))->getFont()->getColor()->setARGB($color);
        $hoja2->getStyle($columnaLetra . ($key + 2))->getFill()->setFillType($relleno)->getStartColor()->setARGB($fondo);
        $indice++;
    }
}

/**
 * FORMATEAR RESULTADOS SEGUN PREGUNTAS
 */

$ultimaColumnaLetra = Coordinate::stringFromColumnIndex($nro_preguntas + 1);

$hoja3->fromArray($hoja3_datos);
$hoja3->setCellValue('A1', 'PREGUNTA');

$hoja3->getStyle('A1:' . $ultimaColumnaLetra . '1')
    ->getFont()
    ->setBold(true);
$hoja3->getStyle('A1:' . $ultimaColumnaLetra . '1')
    ->getFill()
    ->setFillType(Fill::FILL_SOLID)
    ->getStartColor()
    ->setARGB($header_color);
$hoja3->getStyle('A1:' . $ultimaColumnaLetra . '7')
    ->getAlignment()
    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
    ->setVertical(Alignment::VERTICAL_CENTER);
$hoja3->getStyle('A:A')
    ->getAlignment()
    ->setHorizontal(Alignment::HORIZONTAL_LEFT)
    ->setWrapText(true);

/**
 * FORMATEAR MATRIZ
 */

// $hoja4->fromArray($hoja4_datos);
$hoja4->setCellValue('A1', 'COMPETENCIA')
    ->setCellValue('B1', 'CAPACIDAD')
    ->setCellValue('C1', 'DESEMPEÑO')
    ->setCellValue('D1', 'PREGUNTA')
    ->setCellValue('E1', 'ACIERTOS')
    ->setCellValue('F1', 'DESACIERTOS')
    ->setCellValue('G1', '% ACIERTOS')
    ->setCellValue('H1', '% DESACIERTOS');

foreach($matriz as $key => $value) {
    $hoja4->setCellValue('A' . ($key + 2), $value->competencia);
    $hoja4->setCellValue('B' . ($key + 2), $value->capacidad);
    $hoja4->setCellValue('C' . ($key + 2), $value->desempeno);
    $hoja4->setCellValue('D' . ($key + 2), $value->pregunta_nro);
    $hoja4->setCellValue('E' . ($key + 2), $value->aciertos);
    $hoja4->setCellValue('F' . ($key + 2), $value->desaciertos);
    $hoja4->setCellValue('G' . ($key + 2), $value->porcentaje_aciertos);
    $hoja4->setCellValue('H' . ($key + 2), $value->porcentaje_desaciertos);
}

/**
 * FORMATEAR AGRUPADO POR IE
 */

if( $filtros->tipo_reporte == 'IE' ) {
    $hoja5->setCellValue('A1', 'ITEM')
        ->setCellValue('B1', 'IE')
        ->setCellValue('C1', 'UGEL')
        ->setCellValue('D1', 'DISTRITO')
        ->setCellValue('E1', 'TOTAL');

    foreach ($niveles as $key_nivel => $nivel) {
        $columnaLetra = Coordinate::stringFromColumnIndex(6 + $key_nivel);
        $hoja5->setCellValue($columnaLetra . '1', $nivel->nivel_logro);
    }

    $hoja5->getStyle('A1:' . $columnaLetra . '1')
        ->getFont()
        ->setBold(true);
    $hoja5->getStyle('A1:' . $columnaLetra . '1')
        ->getFill()
        ->setFillType(Fill::FILL_SOLID)
        ->getStartColor()
        ->setARGB($header_color);
    $hoja5->getStyle('A1:' . $columnaLetra . count($ies) + 1)
        ->getAlignment()
        ->setHorizontal(Alignment::HORIZONTAL_CENTER)
        ->setVertical(Alignment::VERTICAL_CENTER);
    $hoja5->getStyle('B:D')
        ->getAlignment()
        ->setHorizontal(Alignment::HORIZONTAL_LEFT)
        ->setWrapText(true);

    foreach( $ies as $key => $value ) {
        $hoja5->setCellValue('A' . ($key + 2), $key + 1)
            ->setCellValue('B' . ($key + 2), $value->agrupado)
            ->setCellValue('C' . ($key + 2), $value->ugel)
            ->setCellValue('D' . ($key + 2), $value->distrito)
            ->setCellValue('E' . ($key + 2), $value->total);

        foreach ($niveles as $key_nivel => $nivel) {
            $columnaLetra = Coordinate::stringFromColumnIndex(6 + $key_nivel);
            $nivel_logro_id = strval($nivel->nivel_logro_id);
            $hoja5->setCellValueExplicit($columnaLetra . ($key + 2), intval($value?->$nivel_logro_id ?? 0) / 100, DataType::TYPE_NUMERIC);
            $hoja5->getStyle($columnaLetra . ($key + 2))->getNumberFormat()->setFormatCode('0.00%');
        }
    }
}

$hoja4->getStyle('A1:H1')
    ->getFont()
    ->setBold(true);
$hoja4->getStyle('A1:H1')
    ->getFill()
    ->setFillType(Fill::FILL_SOLID)
    ->getStartColor()
    ->setARGB($header_color);
$hoja4->getStyle('A1:H' . $nro_preguntas + 1)
    ->getAlignment()
    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
    ->setVertical(Alignment::VERTICAL_CENTER);

if( $filtros->tipo_reporte == 'IE' ) {
    $hojas = [$hoja1, $hoja2, $hoja3, $hoja4, $hoja5];
} else {
    $hojas = [$hoja1, $hoja2, $hoja3, $hoja4];
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

// Corregir estilos en hoja de matriz (multiples lineas de texto)
$hoja4->getColumnDimension('A')->setAutoSize(false)->setWidth(40);
$hoja4->getColumnDimension('B')->setAutoSize(false)->setWidth(40);
$hoja4->getColumnDimension('C')->setAutoSize(false)->setWidth(40);
$hoja4->getStyle('A1:C'. $nro_preguntas + 1)
    ->getAlignment()
    ->setHorizontal(Alignment::HORIZONTAL_LEFT)
    ->setWrapText(true);
for($pregunta = 1; $pregunta <= $nro_preguntas; $pregunta++) {
    $hoja4->getRowDimension($pregunta)
        ->setRowHeight(-1);
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
header('Content-Disposition: attachment; filename="'. urlencode('resultados-ere.xlsx') .'"');
$writer->save('php://output');

?>