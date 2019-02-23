<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait PasswordGrantProxy
{
    private $messages = [
        400 => 'Invalid Request. Please enter a username or a password.',
        401 => 'Your credentials are incorrect. Please try again.',
        500 => 'Something went wrong'
    ];

    public function getToken(Request $request)
    {
        $http = new \GuzzleHttp\Client;
        $credentials = json_decode($request->getContent());
        try {
            $response = $http->post(config('services.passport.login_endpoint'), [
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' => config('services.passport.client_id'),
                    'client_secret' => config('services.passport.client_secret'),
                    'username' => $credentials->email,
                    'password' => $credentials->password
                ]
            ]);

            return response()->json([
                'data' => json_decode($response->getBody())
            ], $response->getStatusCode());
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            throw new \Error($this->messages[$e->getCode()] ?? $e->getMessage(), $e->getCode());
        }
    }
}
