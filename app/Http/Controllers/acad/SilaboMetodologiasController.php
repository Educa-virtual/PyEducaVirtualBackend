<?php

namespace App\Http\Controllers\acad;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Hashids\Hashids;

class SilaboMetodologiasController extends Controller
{
    protected $hashids;
    protected $idSilMetId;
    protected $iTipoMetId;
    protected $iSilaboId;


    public function __construct()
    {
        $this->hashids = new Hashids('PROYECTO VIRTUAL - DREMO', 50);
    }

    public function list(Request $request)
    {
        $request->validate(
            [
                'opcion' => 'required',
            ],
            [
                'opcion.required' => 'Hubo un problema al obtener la acción',
            ]
        );
        if ($request->idSilMetId) {
            $idSilMetId = $this->hashids->decode($request->idSilMetId);
            $idSilMetId = count($idSilMetId) > 0 ? $idSilMetId[0] : $idSilMetId;
        }
        if ($request->iTipoMetId) {
            $iTipoMetId = $this->hashids->decode($request->iTipoMetId);
            $iTipoMetId = count($iTipoMetId) > 0 ? $iTipoMetId[0] : $iTipoMetId;
        }
        if ($request->iSilaboId) {
            $iSilaboId = $this->hashids->decode($request->iSilaboId);
            $iSilaboId = count($iSilaboId) > 0 ? $iSilaboId[0] : $iSilaboId;
        }


        $parametros = [
            $request->opcion,
            $request->valorBusqueda ?? '-',

            $idSilMetId                     ?? NULL,
            $iTipoMetId                     ?? NULL,
            $iSilaboId                      ?? NULL,
            $request->cSilMetDescripcion    ?? NULL,

            $request->iCredId

        ];

        try {
            $data = DB::select('exec acad.Sp_SEL_silaboMetodologias
                ?,?,?,?,?,?,?', $parametros);

            foreach ($data as $key => $value) {
                $value->idSilMetId = $this->hashids->encode($value->idSilMetId);
                $value->iTipoMetId = $this->hashids->encode($value->iTipoMetId);
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
    public function store(Request $request)
    {
        $request->validate(
            [
                'opcion' => 'required',
            ],
            [
                'opcion.required' => 'Hubo un problema al obtener la acción',
            ]
        );
        if ($request->idSilMetId) {
            $idSilMetId = $this->hashids->decode($request->idSilMetId);
            $idSilMetId = count($idSilMetId) > 0 ? $idSilMetId[0] : $idSilMetId;
        }
        if ($request->iTipoMetId) {
            $iTipoMetId = $this->hashids->decode($request->iTipoMetId);
            $iTipoMetId = count($iTipoMetId) > 0 ? $iTipoMetId[0] : $iTipoMetId;
        }
        if ($request->iSilaboId) {
            $iSilaboId = $this->hashids->decode($request->iSilaboId);
            $iSilaboId = count($iSilaboId) > 0 ? $iSilaboId[0] : $iSilaboId;
        }

        try {
            switch ($request->opcion) {
                case 'GUARDARxiSilaboId':
                    $parametros = [
                        $iTipoMetId                     ?? NULL,
                        $iSilaboId                      ?? NULL,
                        $request->cSilMetDescripcion    ?? NULL,      
                        $request->iCredId
                    ];
                    $data = DB::select('exec acad.Sp_INS_silaboMetodologias
                ?,?,?,?', $parametros);
                    break;
                case 'ACTUALIZARxidSilMetId':
                    $parametros = [
                        $idSilMetId                     ?? NULL,
                        $iTipoMetId                     ?? NULL,
                        $request->cSilMetDescripcion    ?? NULL,
                        $request->iCredId
                    ];
                    $data = DB::select('exec acad.Sp_UPD_silaboMetodologias
                ?,?,?,?', $parametros);
                    break;
                case 'ELIMINARxidSilMetId':
                    $parametros = [
                        $idSilMetId                     ?? NULL,
                        $request->iCredId,
                    ];
                    $data = DB::select('exec acad.Sp_DEL_silaboMetodologias
                ?,?', $parametros);
                    break;
            }
           
            if ($data[0]->idSilMetId > 0) {

                $response = ['validated' => true, 'mensaje' => 'Se guardó la información exitosamente.'];
                $codeResponse = 200;
            } else {
                $response = ['validated' => false, 'mensaje' => 'No se ha podido guardar la información.'];
                $codeResponse = 500;
            }
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
            $codeResponse = 500;
        }

        return new JsonResponse($response, $codeResponse);
    }
}
