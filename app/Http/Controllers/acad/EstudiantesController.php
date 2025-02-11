<?php

namespace App\Http\Controllers\acad;

use App\Http\Controllers\api\grl\PersonaController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Hashids\Hashids;
use Illuminate\Http\JsonResponse;

class EstudiantesController extends Controller
{
    protected $hashids;
    protected $iEstudianteId;

    public function __construct()
    {
        $this->hashids = new Hashids(config('hashids.salt'), config('hashids.min_length'));
    }

    public function obtenerCursosXEstudianteAnioSemestre(Request $request)
    {
        $request->validate(
            [
                'iEstudianteId' => 'required',
                'iYearId' => 'required',
            ],
            [
                'iEstudianteId.required' => 'Hubo un problema al obtener el iEstudianteId',
                'iYearId.required' => 'Hubo un problema al obtener el iYearId',
            ]
        );

        $parametros = [
            $request->iEstudianteId,
            $request->iYearId
        ];

        try {
            $data = DB::select("execute acad.Sp_SEL_cursosXEstudianteAnioSemestre ?,?", $parametros);

            foreach ($data as $key => $value) {
                $value->iCursoId = $this->hashids->encode($value->iCursoId);
                $value->iSilaboId = $this->hashids->encode($value->iSilaboId);
            }

            $response = ['validated' => true, 'message' => 'se obtuvo la información', 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
            $codeResponse = 500;
        }

        return new JsonResponse($response, $codeResponse);
    }

    public function guardarEstudiantePersona(Request $request)
    {
        // primero guardar como persona
        $request->merge([
            'iTipoPersId' => 1, // Siempre persona natural
        ]);

        $this->validateGuardarPersona($request);
        $parametros = [
            1, // iTipoPersId
            $request->iTipoIdentId,
            $request->cPersDocumento,
            $request->cPersPaterno,
            $request->cPersMaterno,
            $request->cPersNombre,
            $request->cPersSexo,
            $request->dPersNacimiento,
            $request->iTipoEstCivId,
            $request->cPersFotografia,
            $request->cPersRazonSocialNombre,
            $request->cPersRazonSocialCorto,
            $request->cPersRazonSocialSigla,
            $request->cPersDomicilio,
            $request->iCredId,
        ];

        DB::beginTransaction();

        try {
            $data = DB::select('EXEC grl.Sp_INS_personas ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
        } catch (\Exception $e) {
            DB::rollBack();
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
            $codeResponse = 500;
            return new JsonResponse($response, $codeResponse);
        }

        // luego guardar como estudiante
        $parametros = [
            $data[0]->iPersId,
            1, // iCurrId
            $request->cPersNombre,
            $request->cPersPaterno,
            $request->cPersMaterno,
            $request->dPersNacimiento,
            $request->cPersCertificado,
            $request->cPersDomicilio,
            $request->iCredId,
        ];

        try {
            $data = DB::select('EXEC acad.Sp_INS_estudiantes ?,?,?,?,?,?,?,?,?', $parametros);
            $response = ['validated' => true, 'message' => 'Se obtuvo la información', 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            DB::rollBack();
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
            $codeResponse = 500;
        }

        DB::commit();
        return new JsonResponse($response, $codeResponse);
    }

    private function validateGuardarPersona(Request $request){
        return $request->validate([
            'iTipoPersId' => 'required|integer',
            'iTipoIdentId' => 'required|integer',
            'cPersDocumento' => 'required|string|min:8|max:15',
            'cPersPaterno' => 'nullable|string|max:50',
            'cPersMaterno' => 'nullable|string|max:50',
            'cPersNombre' => 'required|string|max:50',
            'cPersSexo' => 'required|size:1',
            'dPersNacimiento' => 'nullable|date',
            'iTipoEstCivId' => 'nullable',
            'cPersFotografia' => 'nullable|string',
            'cPersRazonSocialCorto' => 'nullable|string|max:100',
            'cPersRazonSocialSigla' => 'nullable|string|max:50',
            'cPersDomicilio' => 'nullable|string',
            'iCredId' => 'nullable|integer',
        ]);
    }
}
