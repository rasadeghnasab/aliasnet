<?php

namespace App\Http\Controllers\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\GetTokenRequest;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function getToken(GetTokenRequest $request)
    {
        if (!$this->attempt($request->get('Username'), $request->get('Password'))) {
            return response([
                'success' => false,
                'code' => 401,
                'token' => '',
            ])->setStatusCode(401);
        }

        return response([
            "success" => true,
            "code" => 200,
            "token" => Str::random(),
        ]);
    }

    private function attempt(string $username, string $password): bool
    {
        return strtolower($username) === 'username' && $password === 'somerandompassword!!123';
    }
}
