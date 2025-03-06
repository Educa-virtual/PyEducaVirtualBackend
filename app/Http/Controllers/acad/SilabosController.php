<?php

namespace App\Http\Controllers\acad;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Hashids\Hashids;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Helpers\VerifyHash;
use Exception;

class SilabosController extends Controller
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
            'valorBusqueda',
            'iSilaboId',
            'iSemAcadId',
            'iYAcadId',
            'idDocCursoId'
        ];

        foreach ($fieldsToDecode as $field) {
            $request[$field] = $this->decodeValue($request->$field);
        }

        return  [
            $request->opcion,
            $request->valorBusqueda ?? '-',
            $request->iSilaboId                 ?? NULL,
            $request->iSemAcadId                ?? NULL,
            $request->iYAcadId                  ?? NULL,
            $request->idDocCursoId              ?? NULL,
            $request->dtSilabo                  ?? NULL,
            $request->cSilaboDescripcionCurso   ?? NULL,
            $request->cSilaboCapacidad          ?? NULL,

            $request->iCredId

        ];
    }

    private function encodeFields($item)
    {
        $fieldsToEncode = [
            'valorBusqueda',
            'iSilaboId',
            'iSemAcadId',
            'iYAcadId',
            'idDocCursoId'
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
            $data = DB::select('exec acad.Sp_SEL_silabos
                ?,?,?,?,?,?,?,?,?,?', $parametros);
            
            $data = $this->encodeId($data);

            return new JsonResponse(
                ['validated' => true, 'message' => 'Se obtuvo la información', 'data' => $data],
                200
            );
        } catch (Exception $e) {
            return new JsonResponse(
                ['validated' => false, 'message' => $e->getMessage(), 'data' => []],
                500
            );
        }

    }
    public function actualizar(Request $request){
        $iSilaboId = $this->decodeValue($request->iSilaboId);
        $parametros = [
            $iSilaboId,
            $request->columna,
            $request->valor,
        ];
        
        try {
            $data = DB::select('exec acad.Sp_UPD_silabos ?,?,?', $parametros);
            
            return new JsonResponse(
                ['validated' => true, 'message' => 'Se obtuvo la información', 'data' => $data],
                200
            );
        } catch (Exception $e) {
            return new JsonResponse(
                ['validated' => false, 'message' => $e->getMessage(), 'data' => []],
                500
            );
        }
    }
    public function report(Request $request)
    {
        $request['opcion'] = 2;
        $parametros = $this->validateRequest($request);
        
        $query = DB::select(
            "EXECUTE acad.Sp_SEL_silabos ?,?,?,?,?,?,?,?,?,?",
            $parametros
        );
        
        $formato = $query[0];
        
        

        $respuesta = [
            "query" => $formato,
        ];
        
        $pdf = Pdf::loadView('silabus_reporte', $respuesta)
            ->stream('silabus.pdf');
        return $pdf;
    }
}
