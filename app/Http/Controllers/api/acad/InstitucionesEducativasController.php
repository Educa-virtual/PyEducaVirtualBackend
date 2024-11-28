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


  public function selReglamentoInterno(Request $request)
  {
    $query = DB::select("EXEC grl.SP_SEL_DesdeTablaOVista ?,?,?,?", [
      'acad',
      'institucion_educativas',
      'cIieeUrlReglamentoInterno',
      'iIieeId=' . $request->iIieeId
    ])[0];

    $fileInfo = json_decode($query->cIieeUrlReglamentoInterno);

    if (isset($fileInfo->path) && Storage::disk('file')->exists($fileInfo->path)) {

      // Obtener el contenido del archivo
      $fileContent = Storage::disk('file')->get($fileInfo->path);

      // Crear una respuesta con la información del archivo y el contenido
      return response()->json([
          'name' => $fileInfo->name,
          'size' => $fileInfo->size,
          'lastModified' => $fileInfo->lastModified,
          'mimeType' => $fileInfo->mimeType,
          'value' => base64_encode($fileContent)
      ]);
    } else {
      // Si el archivo no existe, devolver un error
      return response()->json(['error' => 'El archivo no se encuentra disponible.'], 404);
    }
  }

  public function updReglamentoInterno(Request $request)
  {

    if ($request->hasFile('cIieeUrlReglamentoInterno')) {
      // Obtener el archivo del FormData
      $file = $request->file('cIieeUrlReglamentoInterno');

      $query = DB::select("EXEC grl.SP_SEL_DesdeTablaOVista ?,?,?,?", [
        'acad',
        'institucion_educativas',
        'cIieeNombre',
        'iIieeId=' . $request->input('iIieeId')
      ])[0];

      // Obtener el año y mes actuales con Carbon
      $year = Carbon::now()->year;  // Año (AAAA)
      $month = Carbon::now()->month; // Mes (MM)

      // Crear la ruta personalizada dentro de 'storage/app/public'
      $path = 'DocumentosInstitucional/' . $query->cIieeNombre . '/' . $year . '/' . $month;


      // Guardar el archivo en el storage público
      $filePath = $file->storeAs($path, $file->getClientOriginalName(),  ['disk' => 'file']);

      $query = DB::select("EXEC grl.SP_UPD_EnTablaConJSON ?,?,?,?", [
        'acad',
        'institucion_educativas',
        json_encode([
          'cIieeUrlReglamentoInterno' => json_encode([
            'name' => pathinfo($filePath, PATHINFO_BASENAME),
            'size' => Storage::disk('file')->size($filePath),
            'lastModified' => Storage::disk('file')->lastModified($filePath),
            'mimeType' => $file->getMimeType(),
            'path' => $filePath,
          ])
        ]),
        json_encode([['COLUMN_NAME' => 'iIieeId', 'VALUE' => $request->input('iIieeId')]])

      ]);

      return response()->json([
        'message' => 'Archivo subido exitosamente',
        'path' => $query,
      ]);
    } else {
      return response()->json([
        'message' => 'Archivo no encontrado en la solicitud',
      ], 400);
    }
  }
}
