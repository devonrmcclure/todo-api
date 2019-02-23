<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use App\Traits\PasswordGrantProxy;

class LoginController extends Controller
{
    use PasswordGrantProxy;

    public function login(Request $request)
    {
        $credentials = $request->only(['email', 'password']);

        try {
            auth()->attempt($credentials);
            $token = self::getToken($request);

            return self::formatResponse(auth()->user(), json_decode($token->getContent()));
            // TODO: Create custom exception to throw in PasswordGrantProxy.php and catch here.
        } catch (\Error $e) {
            return response()->json(['message' => $e->getMessage()])->setStatusCode($e->getCode());
        }
    }

    protected function formatResponse($user, $token)
    {
        return response()->json([
            'name' => $user['name'],
            'email' => $user['email'],
            'token' => $token->data
        ], 200);
    }
}
