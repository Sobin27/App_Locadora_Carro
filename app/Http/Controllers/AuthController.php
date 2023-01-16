<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        //Autenticação do user
        $token = auth('api')->attempt($request->all(['email', 'password']));

        if ($token) {
            return response()->json(['Message' => "Você logou com sucesso", 'Token' => $token], 200);
        }

        return response()->json(['Message' => "Erro ao tentar logar. Usuário não encontrado!"], 403);
    }

    public function logout()
    {
        auth('api')->logout();
        return response()->json(['Message' => 'Logout feito com sucesso!!']);
    }

    public function refresh()
    {
        $token = auth('api')->refresh();
        return response()->json(['Token' => $token]);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }
}
