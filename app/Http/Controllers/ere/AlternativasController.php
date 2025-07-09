<?php

namespace App\Http\Controllers\ere;

use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use App\Services\Ere\ExtraerBase64;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Hashids\Hashids;

class AlternativasController extends Controller
{
    protected $hashids;

    public function __construct()
    {
        $this->hashids = new Hashids(config('hashids.salt'), config('hashids.min_length'));
    }

    private function decodeValue($value)
    {
        if (is_null($value)) {
            return null;
        }
        return is_numeric($value) ? $value : ($this->hashids->decode($value)[0] ?? null);
    }

    public function validateRequest(Request $request)
    {
        $request->validate(
            ['opcion' => 'required'],
            ['opcion.required' => 'Hubo un problema al obtener la acción']
        );

        $fieldsToDecode = [
            'valorBusqueda',

            'iAlternativaId',
            'iPreguntaId'
        ];

        foreach ($fieldsToDecode as $field) {
            $request[$field] = $this->decodeValue($request->$field);
        }

        return [
            $request->opcion,
            $request->valorBusqueda ?? '-',
            $request->iAlternativaId              ??  NULL,
            $request->iPreguntaId                 ??  NULL,
            $request->cAlternativaDescripcion     ??  NULL,
            $request->cAlternativaLetra           ??  NULL,
            $request->bAlternativaCorrecta        ??  NULL,
            $request->cAlternativaExplicacion     ??  NULL,

            $request->iCredId                     ??  NULL,
            $request->json_alternativas           ??  NULL
        ];
    }

    private function encodeFields($item)
    {
        $fieldsToEncode = [
            'iAlternativaId',
            'iPreguntaId'
        ];

        foreach ($fieldsToEncode as $field) {
            if (isset($item->$field)) {
                $item->$field = $this->hashids->encode($item->$field);
            }
        }

        return $item;
    }

    public function encodeId($data)
    {
        return array_map([$this, 'encodeFields'], $data);
    }

    public function handleCrudOperation(Request $request)
    {
        try {
            $parametros = $this->validateRequest($request);
            $alternativas = json_decode($parametros[9], true);
            foreach ($alternativas as $key => $alternativa) {
                $alternativas[$key]['cAlternativaDescripcion'] = ExtraerBase64::extraer(
                    $alternativa['cAlternativaDescripcion'],
                    $request->iPreguntaId,
                    'alternativa'
                );
            }
            $parametros[9] = json_encode($alternativas);
            $data = DB::select('exec ere.Sp_INS_UPD_preguntas ?,?,?,?,?,?,?,?,?,?', $parametros);
            if ($data[0]->iAlternativaId > 0) {
                return FormatearMensajeHelper::ok('Se guardó la información');
            } else {
                throw new Exception('No se ha podido guardar la información');
            }
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }
}
