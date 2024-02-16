<?php

namespace App\Http\Middleware;

use App\Models\Admin;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Helpers\Helper;

class ApiAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $token = $request->header('Authorization');
        $admin = (new Helper)->get_user($request->admin);
        $authenticate = true;

        // check admin token
        if ($admin == null) {
            return response()->json([
                "errors" => [
                    "message" => [
                        "user author not found."
                    ]
                ]
            ])->setStatusCode(404);
        }


        if (!$token) {
            $authenticate = false;
        }

        $user = Admin::where('token', $token)->first();

        if (!$user) {
            $authenticate = false;
        } elseif ($user->username != $admin['name']) {
            return response()->json([
                "errors" => [
                    "message" => [
                        "unauthorized url parameter for this user"
                    ]
                ]
            ])->setStatusCode(401);
        } else {
            Auth::login($user);
        }

        // check if user is not found
        if ($admin == null) {
            $authenticate = false;
        }


        if ($authenticate) {
            return $next($request);
        } else {
            return response()->json([
                "errors" => [
                    "message" => [
                        "unauthorized"
                    ]
                ]
            ])->setStatusCode(401);
        }
    }
}
