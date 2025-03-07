<?php
//use App\Http\Controllers\Controller;
//use Illuminate\Http\Request;
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class VacantesController extends Controller
{
    public function guardarVacantes(Request $request)
    {
        //return "OKAS";
        // Validar los datos recibidos
        $validator = Validator::make($request->all(), [
            'vacantes' => 'required|array',
            'vacantes.*.iNivel_grado' => 'required|integer',
            'vacantes.*.cVacantesRegular' => 'required|integer',
            'vacantes.*.cVacanteNEE' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Obtener los datos del request
        $vacantes = $request->input('vacantes');

        // Insertar los datos en la base de datos
        try {
            DB::beginTransaction();

            foreach ($vacantes as $vacante) {
                DB::table('acad.vacantes_ies')->insert([
                    'iNivelGradoId' => $vacante['iNivel_grado'],
                    'iVacantesRegular' => $vacante['cVacantesRegular'],
                    'iVacantesNEE' => $vacante['cVacanteNEE'],
                ]);
            }

            DB::commit();

            return response()->json(['message' => 'Vacantes guardadas correctamente'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al guardar las vacantes: ' . $e->getMessage()], 500);
        }
    }
}