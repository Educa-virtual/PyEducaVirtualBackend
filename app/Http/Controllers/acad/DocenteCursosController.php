<?php

namespace App\Http\Controllers\acad;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Hashids\Hashids;

class DocenteCursosController extends Controller
{
    protected $hashids;

    public function __construct()
    {
        $this->hashids = new Hashids('PROYECTO VIRTUAL - DREMO', 50);
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
            'valorBusqueda', 'idDocCursoId', 'iSemAcadId',
            'iYAcadId', 'iDocenteId', 'iIeCursoId'
        ];

        foreach ($fieldsToDecode as $field) {
            $request[$field] = $this->decodeValue($request->$field);
        }

        return [
            $request->opcion,
            $request->valorBusqueda ?? '-',
            $request->idDocCursoId ?? null,
            $request->iSemAcadId ?? null,
            $request->iYAcadId ?? null,
            $request->iDocenteId ?? null,
            $request->iIeCursoId ?? null,
            $request->cDocCursoObservaciones ?? null,
            $request->iDocCursoHorasLectivas ?? null,
            $request->iEstado ?? null,
            $request->iSesionId ?? null,
            $request->iCredId
        ];
    }

    private function encodeFields($item)
    {
        $fieldsToEncode = [
            'idDocCursoId', 'iSemAcadId', 'iYAcadId', 'iIeCursoId',
            'iSilaboId', 'iCursoId', 'iNivelGradoId', 'iSeccionId',
            'iGradoId', 'iDocenteId'
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

    public function list(Request $request)
    {
        $parametros = $this->validateRequest($request);

        try {
            $data = DB::select('exec acad.Sp_SEL_docenteCursos ?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
            $data = $this->encodeId($data);
            
            return new JsonResponse(
                ['validated' => true, 'message' => 'Se obtuvo la información', 'data' => $data],
                200
            );
        } catch (\Exception $e) {
            return new JsonResponse(
                ['validated' => false, 'message' => $e->getMessage(), 'data' => []],
                500
            );
        }
    }
}
