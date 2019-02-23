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

    protected $rules = [
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'string', 'min:6'],
    ];

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 422);
        }

        User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => bcrypt($request['password']),
        ]);

        // User was created, so try "logging" them in and getting the token...
        try {
            $token = self::getToken($request);
            return self::formatResponse($validator->validated(), json_decode($token->getContent()));
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
