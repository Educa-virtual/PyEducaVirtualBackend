<?php

namespace App\Http\Controllers;

use App\Mail\CodigoMail;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function index(Request $request)
    {
        $iPersId = $request->iPersId;
        $correo = 'jhoand60@gmail.com';
        $cCodeVerif = mt_rand(100000,999999);
        
        $mailData = [
            'title' => 'Codigo de Verificacion',
            'body'  => $cCodeVerif
        ];
        
        // DB::select(select bCodeVerif from seg.credenciales where iPersId = ?,[$iPersId]);

        Mail::to($correo)->send(new CodigoMail($mailData)); //Verificar que se envie para poder actualizar

        DB::update('update seg.credenciales set cCodeVerif = ? where iPersId = ?', [$cCodeVerif,$iPersId]);
        try{
            $response = [
                'validated' => true, 
                'message' => 'Email enviado..',
                'data' => [],
            ];

            $estado = 200;

        }catch(Exception $e){
            $response = [
                'validated' => true, 
                'message' => $e->getMessage(),
                'data' => [],
            ];
                        
            $estado = 500;
        }
        return new JsonResponse($response,$estado);
    }
    public function comparar(Request $request){
        $cCodeVerif = $request->cCodeVerif;
        $iPersId = $request->iPersId;
        $data = DB::update("UPDATE seg.credenciales
                            SET bCodeVerif = 1
                            WHERE iPersId = ? AND cCodeVerif = ?",[$iPersId,$cCodeVerif]);

        try{
            $response = [
                'validated' => $data ? true : false, 
                'message' => '',
                'data' => [],
            ];

            $estado = 200;

        }catch(Exception $e){
            $response = [
                'validated' => true, 
                'message' => $e->getMessage(),
                'data' => [],
            ];
                        
            $estado = 500;
        }
        return new JsonResponse($response,$estado);
    }
}
