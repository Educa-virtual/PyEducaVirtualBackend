<?php

namespace App\Http\Controllers\acad;

use App\Helpers\ResponseHandler;
use App\Helpers\VerifyHash;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Hashids\Hashids;
use Illuminate\Support\Facades\Storage;

class DocenteCursosController extends Controller
{
    protected $hashids;

    public function __construct()
    {
        $this->hashids = new Hashids('PROYECTO VIRTUAL - DREMO', 50);
    }

    public function descargarArchivos(Request $request){
        $archivo = $request->archivo;
        
        if (!Storage::disk('public')->exists($archivo)) {
            throw new Exception('El archivo no existe');
        }
        
        $archivo = Storage::disk('public')->get($archivo);
        return $archivo;
    }

    public function guardarProgramacionCurricular(Request $request){
        $iPersId = VerifyHash::decodes($request->iPersId);
        $documento = $request->file('archivo');
        $nombre = $documento->getClientOriginalName();
        $extension = $documento->getClientOriginalExtension();
        $peso = $documento->getSize();
        $folder = 'programacionCurricular/'.$iPersId;
        $portafolio = $request->portafolio;
        
        if ($documento) {
            $generado = Storage::disk('public')->put($folder,$documento);
            $ruta = $folder.'/'.basename($generado);
        }

        $folder = [
            [
            "portafolio" => $portafolio, 
            "nombre" => $nombre,
            "ruta" => $ruta,
            "extension" => $extension,
            "peso" => $peso,
            ]
        ];
        
        $convertir = json_encode($folder);

        $parametros = [
            $request->idDocCursoId,
            $request->iSilaboId,
            $request->iYAcadId,
            $convertir,
        ];

        try {
            $data = DB::select('exec acad.Sp_UPD_programacionCurricular ?,?,?,?', $parametros);
            $datos = [
                "estado" => $data[0]->resultado,
                "documento" => $convertir,
            ];
            return new JsonResponse(
                ['validated' => true, 'message' => 'Se obtuvo la información', 'data' => $datos],
                200
            );
        } catch (Exception $e) {
            return new JsonResponse(
                ['validated' => false, 'message' => $e->getMessage(), 'data' => []],
                500
            );
        }
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
    public function buscarDocenteCurso(Request $request){

        $opcion=$request->opcion;
        $iDocenteId=$request->iDocenteId;
        $iYAcadId=$request->iYAcadId;
        $iSedeId=$request->iSedeId;
        $iIieeId=$request->iIieeId;
        
        $docente = VerifyHash::decodesxId($iDocenteId);

        $solicitud = [
            $opcion
            ,$docente
            ,$iYAcadId
            ,$iSedeId
            ,$iIieeId
        ];
        
        $query = 'EXEC acad.Sp_SEL_docentexcursoxgradoxseccion '.str_repeat('?,',count($solicitud)-1).'?';
        try {
            $data = DB::select($query, $solicitud);
            return ResponseHandler::success($data);
        } catch (Exception $e) {
            return ResponseHandler::error("Error para obtener Datos ",500,$e->getMessage());
        }
    }
    public function importarSilabos(Request $request){

        $iSilaboId=$request->iSilaboId;
        $idDocCursoId=$request->idDocCursoId;

        $solicitud = [
            VerifyHash::decodesxId($iSilaboId),
            VerifyHash::decodesxId($idDocCursoId),
        ];
        
        $query = 'EXEC acad.Sp_INS_importarSilabos '.str_repeat('?,',count($solicitud)-1).'?';
    
        try {
            $data = DB::select($query, $solicitud);
            return ResponseHandler::success($data);
        } catch (Exception $e) {
            return ResponseHandler::error("Error para obtener Datos ",500,$e->getMessage());
        }
    }
}
