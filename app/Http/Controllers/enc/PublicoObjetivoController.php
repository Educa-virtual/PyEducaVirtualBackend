<?php

namespace App\Http\Controllers\enc;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Http\JsonResponse;
use App\Helpers\VerifyHash;
use Illuminate\Http\Response;

class PublicoObjetivoController extends Controller
{
    public function listarPublicoObjetivo(Request $request)
    {
        try {
            $fieldsToDecode = ['iTipPubId', 'iCredId'];
            $datos = json_decode($request->cDirigido, true);

            // Validación del JSON
            if (!is_array($datos)) {
                return response()->json([
                    'validated' => false,
                    'message' => 'Formato de datos inválido.',
                    'data' => []
                ], Response::HTTP_BAD_REQUEST);
            }

            // Inicializar variables
            $espDremo = [];
            $data = [
                'directores' => [],
                'docentes' => [],
                'estudiantes' => [],
                'apoderados' => [],
                'espDremo' => [],
                'espUgel' => [],
                'ugeles' => [],
            ];

            foreach ($datos as &$item) {
                // Validar hash solo del campo actual sin modificar $request globalmente
                $tempRequest = $request->merge(['iTipPubId' => $item['iTipPubId']]);
                $validatedRequest = VerifyHash::validateRequest($tempRequest, $fieldsToDecode);
                $item['iTipPubId'] = $validatedRequest->iTipPubId;

                switch ((int)$item['iTipPubId']) {
                    case 1: // Directores
                        $data['directores'][] = $item;
                        break;

                    case 2: // Docentes
                        $item['cTipPubNombre'] = 'Estudiante';
                        $data['docentes'][] = $item;
                        break;

                    case 3: // Estudiantes
                        $item['cTipPubNombre'] = 'Apoderado';
                        $data['estudiantes'][] = $item;
                        break;

                    case 4: // Apoderados
                        $item['cTipPubNombre'] = 'Apoderado';
                        $data['apoderados'][] = $item;
                        break;

                    case 5: // Especialistas DREMO
                        if ($item['bSeleccionado'] == 1) {
                            $espDremo = DB::select(
                                'exec [acad].[Sp_SEL_EspDirDocEstApodDesdeJsonOpcion] @_opcion = ?',
                                ['ESP_DREMO']
                            );
                            $data['espDremo'] = $espDremo;
                        }
                        break;

                    case 6: // Especialistas UGEL
                         if ($item['bSeleccionado'] == 1) {
                            $espDremo = DB::select(
                                'exec [acad].[Sp_SEL_EspDirDocEstApodDesdeJsonOpcion] @_opcion = ?',
                                ['ESP_UGEL']
                            );
                            $data['ugeles'] = $espDremo;
                        }
                        break;
                }
            }

            return response()->json([
                'validated' => true,
                'message' => 'Se ha obtenido exitosamente.',
                'data' => $data
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'validated' => false,
                'message' => $e->getMessage(),
                'data' => []
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
