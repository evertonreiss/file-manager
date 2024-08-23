<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\V1\sendResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthContoller extends Controller
{
    use sendResponse;

    public function generateToken(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->sendResponse(false, 'Credenciais inválidas', null, ['credentials' => 'Os dados fornecidos são inválidos'], 401);
        }

        $token['token'] = $user->createToken($request->userAgent())->plainTextToken;

        return $this->sendResponse(true, 'Token criado com sucesso',  $token, null, 201);
    }
}
