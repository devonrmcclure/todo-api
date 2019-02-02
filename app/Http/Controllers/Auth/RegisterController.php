<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\PasswordGrantProxy;

class RegisterController extends Controller
{
	use PasswordGrantProxy;

	public function create(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'name' => ['required', 'string', 'max:255'],
			'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
			'password' => ['required', 'string', 'min:6'],
		]);

	
		if ($validator->fails()) {
			return response()->json(['message' => $validator->errors()], 422);
		}

		User::create([
			'name' => $request['name'],
			'email' => $request['email'],
			'password' => bcrypt($request['password']),
		]);
		
		$token = self::getToken($request);
		
		if ($token->getStatusCode() != 200) {
			return response()->json($token->getContent());
		}

		return self::formatResponse($validator->validated(), json_decode($token->getContent()));
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
