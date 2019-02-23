<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\User as UserResource;

class UserController extends Controller
{
    public function show(User $user)
    {
        if ($user->id == auth()->user()->id) {
            return new UserResource($user);
        }

        return response()->json(['message' => 'Unauthorized'])->setStatusCode(401);
    }

    public function update(User $user): JsonResponse
    {
        if ($user->id == auth()->user()->id) {
            $attributes = request()->validate([
                'name' => 'string',
                'email' => 'email',
                'password' => 'min:6'
            ]);

            $user->update($attributes);

            return response()->json($user);
        }
        return response()->json(['message' => 'Unauthorized'])->setStatusCode(401);
    }

    public function destroy(User $user): JsonResponse
    {
        if ($user->id == auth()->user()->id) {
            $user->delete();

            return response()->json([
                'message' => 'User deleted successfully'
            ])->setStatusCode(200);
        }

        return response()->json(['message' => 'Unauthorized'])->setStatusCode(401);
    }
}
