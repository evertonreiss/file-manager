<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\V1\sendResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthContoller extends Controller
{
    use sendResponse;

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'password' => 'required|min:8|max:255'
          ]);

        if($validator->fails()){
            return $this->sendResponse(false, 'Erro ao fazer login', $request->all(), $validator->errors(), 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->sendResponse(false, 'Credenciais inválidas', null, ['credentials' => 'Os dados fornecidos são inválidos'], 401);
        }

        // $token['token'] = $user->createToken($request->userAgent())->plainTextToken;

        $data = [
            'token' => $user->createToken($request->userAgent())->plainTextToken,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email
            ]
        ];

        return $this->sendResponse(true, 'Token criado com sucesso',  $data, null, 201);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|min:8|max:255'
          ]);

        if($validator->fails()){
            return $this->sendResponse(false, 'Erro ao cadastrar usuário', $request->all(), $validator->errors(), 422);
        }

        $validatedData = $validator->validated();
        $validatedData['password'] = Hash::make($validatedData['password']);

        $user = User::create($validatedData);

        return $this->sendResponse(true, 'Usuário cadastrado com sucesso', $user, null, 201);
    }
}
