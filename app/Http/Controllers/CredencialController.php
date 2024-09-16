<?php

namespace App\Http\Controllers;

use App\Models\SegCredenciales;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class CredencialController extends Controller
{
    public function login(Request $request){
        $user = $request->user;
        $pass = $request->pass;
        $query = DB::select('EXECUTE seg.Sp_SEL_credencialesXcCredUsuarioXcClave ?,?',[$user,$pass]);
        return new JsonResponse([
            "msg"   =>  $query,
        ],200);
    }
}
