<?php

namespace App\Http\Controllers\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\GetTokenRequest;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function getToken(GetTokenRequest $request)
    {
        $response = [
            "success" => true,
            "code" => 200,
            "token" => Str::random(),
        ];

        if (!$this->attempt($request->get('Username'), $request->get('Password'))) {
            $response = [
                'success' => false,
                'code' => 401,
                'token' => '',
            ];
        }

        return response($response);
    }

    private function attempt(string $username, string $password): bool
    {
        return $username === '' && $password === 'somerandompassword!!123';
    }
}
