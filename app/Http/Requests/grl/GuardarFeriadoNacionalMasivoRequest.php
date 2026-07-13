<?php

namespace App\Http\Requests\grl;

use App\Http\Requests\GeneralFormRequest;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use DateTime;

class GuardarFeriadoNacionalMasivoRequest extends GeneralFormRequest
{
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'iYAcadId' => $this->route('iYAcadId') ?? $this->input('iYAcadId'),
            'jsonFeriadosNacionales' => $this->formatearDatosEnJson($this->input('jsonFeriadosNacionales')),
        ]);
    }

    public function rules(): array
    {
        return [
            'iYAcadId' => 'integer|required',
            'jsonFeriadosNacionales' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $data = json_decode($value, true);

                    if (json_last_error() !== JSON_ERROR_NONE) {
                        $fail('Ocurrió un error al leer los datos.');
                        return;
                    }

                    if (!is_array($data) || count($data) === 0) {
                        $fail('Debe enviar al menos un feriado nacional.');
                        return;
                    }

                    foreach ($data as $index => $item) {
                        $pos = $index + 1;

                        if (!isset($item['dtFeriado']) || empty($item['dtFeriado'])) {
                            $fail("El feriado #{$pos} no tiene una fecha válida.");
                        }

                        if (!isset($item['cFeriadoNombre']) || empty(trim($item['cFeriadoNombre']))) {
                            $fail("El feriado #{$pos} no tiene un nombre válido.");
                        } elseif (mb_strlen($item['cFeriadoNombre']) > 200) {
                            $fail("El nombre del feriado #{$pos} no puede superar los 200 caracteres.");
                        }

                        if (isset($item['cDocumento']) && mb_strlen($item['cDocumento']) > 150) {
                            $fail("La URL del documento del feriado #{$pos} no puede superar los 150 caracteres.");
                        }
                    }
                },
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'iYAcadId' => 'año académico',
            'jsonFeriadosNacionales' => 'feriados importados',
        ];
    }

    private function formatearDatosEnJson(String $json_hojas)
    {
        $hojas = json_decode($json_hojas, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return json_encode([]);
        }

        $filas = $hojas[0];
        if (!is_array($filas) || count($filas) === 0) {
            return json_encode([]);
        }

        $feriados = [];
        foreach ($filas as $index_fila => $fila) {
            // Extraer datos a partir de la fila 2
            if ($index_fila > 1) {
                // Limpiar datos de la fila
                $fila = array_map('strtoupper', $fila);
                $fila = array_map('trim', $fila);

                // Ignorar filas sin fecha ni nombre
                if ((!isset($fila['A'])) || (!isset($fila['B']))) {
                    continue;
                } else {
                    if (($fila['A'] == '') && ($fila['B'] == '')) {
                        continue;
                    }
                }
                // Formatear resultados de estudiantes en nuevo array
                $dtFeriado = isset($fila['A']) ? Date::excelToDateTimeObject($fila['A']) : null;
                $bFeriadoEsRecuperable = isset($fila['C']) && $fila['C'] != '' ? 1 : 0;
                $feriados[] = array(
                    'dtFeriado' => isset($dtFeriado) ? $dtFeriado->format('Y-m-d') : null,
                    'cFeriadoNombre' => isset($fila['B']) ? $fila['B'] : null,
                    'bFeriadoEsRecuperable' => isset($bFeriadoEsRecuperable) ? $bFeriadoEsRecuperable : null,
                    'cFeriadoDescripcion' => isset($fila['D']) ? $fila['D'] : null,
                    'cDocumento' => isset($fila['E']) ? $fila['E'] : null,
                );
            }
        }
        return str_replace("'", "''", json_encode($feriados));
    }
}
