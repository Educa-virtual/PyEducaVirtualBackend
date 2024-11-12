<?php

namespace App\Http\Controllers\aula;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Hashids\Hashids;

class TareaCabeceraGruposController extends Controller
{
    protected $hashids;
    protected $iTareaCabGrupoId;
    protected $iTareaId;

    // Constructor de la clase
    public function __construct()
    {
        // Inicializa el objeto Hashids usando los parámetros de configuración.
        // 'salt' es una cadena secreta utilizada para personalizar los hash generados,
        // 'min_length' define la longitud mínima del hash generado.
        $this->hashids = new Hashids(config('hashids.salt'), config('hashids.min_length'));
    }


    public function list(Request $request)
    {
        // Validación de los datos de entrada
        $request->validate(
            [
                'opcion' => 'required',  // Se requiere que el parámetro 'opcion' esté presente
            ],
            [
                'opcion.required' => 'Hubo un problema al obtener la acción', // Mensaje de error personalizado si 'opcion' falta
            ]
        );

        // Si se recibe un parámetro 'iTareaId', se decodifica utilizando hashids
        if ($request->iTareaId) {
            // Decodificar el valor de 'iTareaId' utilizando hashids
            $iTareaId = $this->hashids->decode($request->iTareaId);
            // Si hay un valor válido, asignarlo al parámetro, de lo contrario mantener el valor original
            $iTareaId = count($iTareaId) > 0 ? $iTareaId[0] : $iTareaId;
        }

        // Se definen los parámetros a pasar a la consulta almacenada (SP)
        $parametros = [
            $request->opcion,                     // Opción recibida desde la solicitud
            $request->valorBusqueda ?? '-',        // Valor de búsqueda, si no se recibe se asigna un guion
            $iTareaCabGrupoId ?? NULL,             // ID de grupo de tarea cabecera, si no existe se asigna NULL
            $iTareaId ?? NULL,                     // ID de tarea, si no existe se asigna NULL
            $request->cTareaGrupoNombre ?? NULL,   // Nombre de grupo de tarea, si no existe se asigna NULL
            $request->nTareaGrupoNota ?? NULL,     // Nota de grupo de tarea, si no existe se asigna NULL
            $request->cTareaGrupoComentarioDocente ?? NULL, // Comentario del docente, si no existe se asigna NULL
            $request->cTareaGrupoUrl ?? NULL,      // URL de grupo de tarea, si no existe se asigna NULL
            $request->iEstado ?? NULL,             // Estado, si no existe se asigna NULL
            $request->iSesionId ?? NULL,           // ID de sesión, si no existe se asigna NULL
            $request->dtCreado ?? NULL,            // Fecha de creación, si no existe se asigna NULL
            $request->dtActualizado ?? NULL        // Fecha de actualización, si no existe se asigna NULL
        ];

        try {
            // Se ejecuta la consulta almacenada (stored procedure) con los parámetros definidos anteriormente
            $data = DB::select('exec aula.SP_aulaCrudTareaCabeceraGrupos
            ?,?,?,?,?,?,?,?,?,?,?,?', $parametros);

            // Si se obtiene la información, se recorre y se realiza el proceso de codificación de los IDs
            foreach ($data as $key => $value) {
                // Codificar los valores de 'iEscalaCalifId' y 'iTareaId' usando hashids
                $value->iEscalaCalifId = $this->hashids->encode($value->iEscalaCalifId);
                $value->iTareaId = $this->hashids->encode($value->iTareaId);
            }

            // Si la consulta fue exitosa, se prepara la respuesta con código 200 (OK)
            $response = ['validated' => true, 'message' => 'se obtuvo la información', 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            // Si ocurre un error, se captura la excepción y se retorna un error 500 con el mensaje de la excepción
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
            $codeResponse = 500;
        }

        // Se retorna la respuesta en formato JSON con el código de estado correspondiente
        return new JsonResponse($response, $codeResponse);
    }

    public function store(Request $request)
    {
        // Validación de los datos recibidos en la solicitud
        $request->validate(
            [
                'opcion' => 'required', // El campo 'opcion' es obligatorio
            ],
            [
                'opcion.required' => 'Hubo un problema al obtener la acción', // Mensaje personalizado si no se proporciona el campo 'opcion'
            ]
        );

        // Si existe el parámetro 'iTareaId', se decodifica usando 'hashids'
        if ($request->iTareaId) {
            $iTareaId = $this->hashids->decode($request->iTareaId);
            // Si la decodificación es exitosa (al menos un valor), se asigna el primer valor decodificado
            $iTareaId = count($iTareaId) > 0 ? $iTareaId[0] : $iTareaId;
        }

        // Preparación de los parámetros que se enviarán al procedimiento almacenado
        $parametros = [
            $request->opcion,
            $request->valorBusqueda ?? '-',
            $request->iTareaCabGrupoId ?? NULL,
            $iTareaId ?? NULL,
            $request->cTareaGrupoNombre,
            $request->nTareaGrupoNota ?? NULL,
            $request->cTareaGrupoComentarioDocente ?? NULL,
            $request->cTareaGrupoUrl ?? NULL,
            $request->iEstado ?? NULL,
            $request->iSesionId ?? NULL,
            $request->dtCreado ?? NULL,
            $request->dtActualizado ?? NULL
        ];

        try {
            // Llamada al procedimiento almacenado 'SP_aulaCrudTareaCabeceraGrupos' usando los parámetros preparados
            $data = DB::select('exec aula.SP_aulaCrudTareaCabeceraGrupos
            ?,?,?,?,?,?,?,?,?,?,?,?', $parametros);

            // Verificación de si se obtuvo un resultado válido
            if ($data[0]->iTareaCabGrupoId > 0) {
                // Si la tarea se guarda correctamente, se prepara una respuesta exitosa
                $response = ['validated' => true, 'mensaje' => 'Se guardó la información exitosamente.'];
                $codeResponse = 200; // Código de respuesta 200 (OK)
            } else {
                // Si no se guardó la información, se prepara una respuesta de error
                $response = ['validated' => false, 'mensaje' => 'No se ha podido guardar la información.'];
                $codeResponse = 500; // Código de respuesta 500 (Error interno del servidor)
            }
        } catch (\Exception $e) {
            // Si ocurre una excepción durante la ejecución, se captura y se prepara una respuesta de error
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
            $codeResponse = 500; // Código de respuesta 500 (Error interno del servidor)
        }

        // Retorno de la respuesta en formato JSON
        return new JsonResponse($response, $codeResponse);
    }


    public function eliminarTareaCabeceraGrupos(Request $request)
    {
        // Definir los parámetros para la consulta.
        // Se espera que 'iTareaCabGrupoId' sea el identificador de la tarea a eliminar.
        $parametros = [
            $request->iTareaCabGrupoId, // Parámetro enviado por el cliente a través del request
        ];

        try {
            // Ejecutar el procedimiento almacenado en la base de datos, pasando el parámetro necesario.
            // La función `DB::select()` se utiliza para ejecutar el procedimiento `SP_DEL_tareaCabeceraGruposxiTareaCabGrupoId`.
            // El procedimiento elimina una tarea en función de su ID.
            $data = DB::select('exec aula.SP_DEL_tareaCabeceraGruposxiTareaCabGrupoId
                ?', $parametros);

            // Verificar si la respuesta contiene un ID mayor a 0, lo que indica éxito.
            if ($data[0]->iTareaCabGrupoId > 0) {
                // Si la eliminación fue exitosa, se devuelve una respuesta positiva con mensaje de éxito.
                $response = ['validated' => true, 'mensaje' => 'Se guardó la información exitosamente.'];
                $codeResponse = 200; // Código de respuesta HTTP 200 (éxito)
            } else {
                // Si no se pudo eliminar la tarea, se devuelve una respuesta negativa con un mensaje de error.
                $response = ['validated' => false, 'mensaje' => 'No se ha podido guardar la información.'];
                $codeResponse = 500; // Código de respuesta HTTP 500 (error interno)
            }
        } catch (\Exception $e) {
            // Si ocurre un error durante la ejecución del procedimiento, se captura la excepción.
            // El mensaje de error es retornado en la respuesta.
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
            $codeResponse = 500; // Código de respuesta HTTP 500 (error interno)
        }

        // Retornar la respuesta en formato JSON, junto con el código de respuesta adecuado.
        return new JsonResponse($response, $codeResponse);
    }
    // Definición de la función que recibe una solicitud
    public function guardarCalificacionTareaCabeceraGruposDocente(Request $request)
    {
        // Comprobamos si se ha recibido un valor para 'iEscalaCalifId' en la solicitud
        if ($request->iEscalaCalifId) {
            // Decodificamos el valor 'iEscalaCalifId' usando Hashids
            $iEscalaCalifId = $this->hashids->decode($request->iEscalaCalifId);
            // Si se ha decodificado correctamente, tomamos el primer valor, sino asignamos el valor original
            $iEscalaCalifId = count($iEscalaCalifId) > 0 ? $iEscalaCalifId[0] : $iEscalaCalifId;
        }

        // Preparamos los parámetros que se pasarán al procedimiento almacenado
        $parametros = [
            $request->iTareaCabGrupoId,  // ID del grupo de la tarea
            $iEscalaCalifId,              // ID de la escala de calificación
            $request->cTareaGrupoComentarioDocente,  // Comentario del docente sobre la tarea
        ];

        try {
            // Ejecutamos el procedimiento almacenado con los parámetros proporcionados
            $data = DB::select('exec aula.SP_UPD_tareaCabeceraGruposCalificarDocente
                ?,?,?', $parametros);

            // Verificamos si el resultado contiene un ID de grupo de tarea mayor que 0
            if ($data[0]->iTareaCabGrupoId > 0) {
                // Si la operación fue exitosa, se devuelve una respuesta con éxito
                $response = ['validated' => true, 'mensaje' => 'Se guardó la información exitosamente.'];
                $codeResponse = 200;  // Código HTTP 200 (OK)
            } else {
                // Si la operación no fue exitosa, se devuelve un mensaje de error
                $response = ['validated' => false, 'mensaje' => 'No se ha podido guardar la información.'];
                $codeResponse = 500;  // Código HTTP 500 (Error interno del servidor)
            }
        } catch (\Exception $e) {
            // Si ocurre una excepción, capturamos el error y lo devolvemos en la respuesta
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
            $codeResponse = 500;  // Código HTTP 500 (Error interno del servidor)
        }

        // Devolvemos la respuesta en formato JSON
        return new JsonResponse($response, $codeResponse);
    }

    // Definición de la función que recibe una solicitud HTTP
    public function transferenciaTareaCabeceraGrupos(Request $request)
    {
        // Se define un arreglo con los parámetros necesarios que se obtienen de la solicitud
        $parametros = [
            $request->iTareaCabGrupoId,       // ID del grupo de tarea
            $request->iTareaEstudianteId,     // ID de la tarea del estudiante
            $request->iEstudianteId           // ID del estudiante
        ];

        try {
            // Se ejecuta un procedimiento almacenado en SQL Server pasando los parámetros
            $data = DB::select('exec aula.SP_UPD_tareaCabeceraGrupoTransferenciaxiEstudianteIdxiTareaEstudianteIdxiTareaCabGrupoId
            ?,?,?', $parametros);

            // Verifica si la respuesta del procedimiento tiene un valor mayor a 0
            if ($data[0]->iTareaCabGrupoId > 0) {
                // Si es exitoso, devuelve una respuesta positiva con código 200
                $response = ['validated' => true, 'mensaje' => 'Se guardó la información exitosamente.'];
                $codeResponse = 200;
            } else {
                // Si no se guardó, devuelve un mensaje de error con código 500
                $response = ['validated' => false, 'mensaje' => 'No se ha podido guardar la información.'];
                $codeResponse = 500;
            }
        } catch (\Exception $e) {
            // Si ocurre una excepción, captura el error y devuelve el mensaje correspondiente
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
            $codeResponse = 500;
        }
        // Retorna la respuesta en formato JSON con el código de estado
        return new JsonResponse($response, $codeResponse);
    }

    public function entregarEstudianteTareaGrupal(Request $request)
    {
        if ($request->iTareaId) {
            $iTareaId = $this->hashids->decode($request->iTareaId);
            $iTareaId = count($iTareaId) > 0 ? $iTareaId[0] : $iTareaId;
        }
        $parametros = [
            $iTareaId,
            $request->iEstudianteId,
            $request->cTareaGrupoUrl,
        ];

        try {
            $data = DB::select('exec aula.SP_UPD_tareaEstudiantesxEntregarEstudianteTareaGrupal
                ?,?,?', $parametros);

            if ($data[0]->iTareaCabGrupoId > 0) {

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
