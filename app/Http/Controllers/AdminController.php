<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\AdminResource;
use App\Http\Requests\AdminLoginRequest;
use App\Http\Requests\AdminUpdateRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function login(AdminLoginRequest $request): AdminResource
    {
        $data = $request->validated();

        $user = Admin::where('username', $data['username'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw new HttpResponseException(response([
                "errors" => [
                    "message" => [
                        "username or password wrong"
                    ]
                ]
            ], 401));
        }

        $user->token = Str::uuid()->toString();
        $user->save();

        return new AdminResource($user);
    }

    public function get(Request $request): AdminResource
    {
        $user = Auth::user();
        return new AdminResource($user);
    }

    public function create(Request $request): AdminResource
    {
        $data = $request;

        $user = new Admin();
        $user->username = $data['username'];
        $user->password = Hash::make($data['password']);
        $user->wp_password = $data['wp-password'];
        $user->save();

        return new AdminResource($user);
    }

    public function update(AdminUpdateRequest $request): AdminResource
    {
        $user = Auth::user();


        if (isset($request['username'])) {
            $user->username = $request['username'];
        }

        if (isset($request['password'])) {
            $user->password = $request['password'];
        }

        $user->save();
        return new AdminResource($user);
    }

    public function logout(Request $request): JsonResponse
    {
        $user = Auth::user();
        $user->token = null;
        $user->save();

        return response()->json([
            "data" => true
        ])->setStatusCode(200);
    }
}
