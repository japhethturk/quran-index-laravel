<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    
    public function authorizeAdmin(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            $credentials = $request->only(['email', 'password']);

            if (Auth::attempt($credentials)) {
                $authUser = Auth::user();
                if ($authUser->role === 'admin' || $authUser->role === 'manager' || $authUser->role === 'editor') {
                    $APP_NAME = env("APP_NAME", "PrimeBlog");
                    $authUser->token = $authUser->createToken($APP_NAME, [$authUser->role])->accessToken;
                    return response()->json([
                        'user'=>$authUser->only(['id', 'name', 'token', 'role']),
                        'status' => 'ok',
                        'message' => Helper::message('success', trans('translation.authorize_admin_success'))
                    ]);
                } else {
                    return response()->json([
                        'status' => 'not_authorized',
                        'message' => Helper::message('warn', trans('translation.not_authorized'))
                    ]);
                }
            }
            else {
                return response()->json([
                    'status' => 'failed',
                    'message' => Helper::message('error', trans('translation.login_failed'))
                ]);
            }
        } catch (\Exception $e) {
            return Helper::catchExceptionResponse($e->getMessage());
        }
    }

    public function checkToken(Request $request): JsonResponse
    {
        try {
            $userFromToken = request()->user();
            if (isset($userFromToken->name)){
                return response()->json([
                    'status'=>  'ok',
                ]);
            } else {
                return response()->json([
                    'status'=>'not_found',
                    'message' => [
                        'severity' => 'error',
                        'summary' => trans('error'),
                        'detail' => trans('translation.user_not_defined'),
                        'life' => 4000
                    ],
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'detail' => $e->getMessage()
            ]);
        }
    }


    public function logout(Request $request): JsonResponse
    {
        try {
            if (Auth::check()) {
                $user = Auth::user();
                $user->token()->revoke();
                $user->AuthAcessToken()->delete();
            }
            return response()->json([
                'status'=>'ok',
            ]);
        } catch (\Exception $e){
            return response()->json([
                'status'=>'error',
                'message'=>$e->getMessage()
            ]);
        }
    }

}
