<?php

namespace App\Http\Controllers\api\acad;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class InstitucionesEducativasController extends Controller
{
  public function response($query)
  {
    $response = [
      'validated' => true,
      'message' => '',
      'data' => [],
    ];
    $estado = 200;

    try {
      $response['message'] = 'Se obtuvo la información';
      $response['data'] = $query;
    } catch (Exception $e) {
      $response['message'] = $e->getMessage();
      $estado = 500;
    }

    return new JsonResponse($response, $estado);
  }


  public function selReglamentoInterno()
  {
    $path = storage_path('sample.pdf');

    if (!file_exists($path)) {
      abort(404, 'Archivo no encontrado.');
    }

    return response()->file($path);
  }

  public function updReglamentoInterno(Request $request)
  {

    if ($request->hasFile('cIieeUrlReglamentoInterno')) {
      // Obtener el archivo del FormData
      $file = $request->file('cIieeUrlReglamentoInterno');

    //   $query = DB::select("EXEC acad.SP_UPD_stepCalendarioAcademicoDesdeJsonOpcion ?,?", [
    //     $request->calAcad,
    //     // 'acad',
    //     'updateCalAcademico',
    //     // 'iCalAcadId',
    //     // $request->iCalAcadId,
    // ]);

      $cIieeNombre = 's';

      // Obtener el año y mes actuales con Carbon
      $year = Carbon::now()->year;  // Año (AAAA)
      $month = Carbon::now()->month; // Mes (MM)

      // Crear la ruta personalizada dentro de 'storage/app/public'
      $path = 'DocumentosInstitucional/' . $cIieeNombre . '/' . $year . '/' . $month . '/' . $file->getClientOriginalName();


      // Guardar el archivo en el storage público
      $filePath = $file->store($path, ['disk' => 'file']);

      return response()->json([
        'message' => 'Archivo subido exitosamente',
        'path' => $filePath,
      ]);
    } else {
      return response()->json([
        'message' => 'Archivo no encontrado en la solicitud',
      ], 400);
    }
  }
}
