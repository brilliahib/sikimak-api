<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->only('');
    }

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        if (User::where('email', $data['email'])->exists()) {
            throw new HttpResponseException(
                response([
                    'statusCode' => 400,
                    'message' => 'Email sudah digunakan',
                ], 400)
            );
        }

        if (User::where('phone', $data['phone'])->exists()) {
            throw new HttpResponseException(
                response([
                    'statusCode' => 400,
                    'message' => 'Nomor telepon sudah digunakan',
                ], 400)
            );
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'user' => new UserResource($user),
            'token' => $token,
        ]);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $data = $request->validated();

        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials',
            ], 401);
        }

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'id' => $user->id,
            'token' => $token,
        ]);
    }


    public function getAuth(): JsonResponse
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(
                [
                    'error' => 'User not authenticated',
                ],
                401,
            );
        }

        return response()->json(new UserResource($user));
    }

    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        $data = $request->validated();

        $user = Auth::user();

        if (!$user) {
            return response()->json(
                [
                    'statusCode' => 401,
                    'error' => 'User not authenticated',
                ],
                401,
            );
        }

        if (!Hash::check($data['current_password'], $user->password)) {
            return response()->json(
                [
                    'statusCode' => 422,
                    'message' => 'Incorrect current password',
                ],
                422,
            );
        }

        $user->password = Hash::make($data['new_password']);
        $user->save();

        return response()->json(
            [
                'statusCode' => 200,
                'message' => 'Password successfully updated',
            ],
            200,
        );
    }
}
