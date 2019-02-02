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
		$user;

		if (auth()->attempt($credentials)) {
			$token = self::getToken($request);

			if ($token->getStatusCode() != 200) {
				return response()->json($token->getContent());
			}

			return self::formatResponse(auth()->user(), json_decode($token->getContent()));
		}

		return response()->json([
			'message' => 'Invalid credentials'
		])->setStatusCode(401);
	}

	protected function formatResponse($user, $token)
	{
		return response()->json([
			'name' => $user['name'],
			'email' => $user['email'],
			'token' => $token
		], 200);
	}
}
