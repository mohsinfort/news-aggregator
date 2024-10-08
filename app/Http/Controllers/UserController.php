<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Repositories\UserRepository;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(RegisterUserRequest $request)
    {
        try{
            $this->userRepository->createUser($request->name, $request->email, $request->password);

            return response()->json([
                'message' => Lang::get('auth.successfullyCreatedAccount')
            ], 200);
        } catch (Exception $e) {

            Log::error('Exception: UserController@register', [$e->getMessage()]);

            return response()->json(['errors' => Lang::get('general.pleaseContactSupportWithCode', ['code' => 500])], 500);
        }
    }

    public function login(LoginRequest $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if(! Auth::attempt($credentials)) {
            return response()->json(['errors' => Lang::get('auth.invalidEmailOrPassword')], 401);
        }

        $user = $this->userRepository->getUserByEmail($request->email);

        return response()->json([
            'access_token' => $user->createToken("API TOKEN")->plainTextToken,
            'message' => Lang::get('auth.successfullyLoggedIn'),
        ],200);
    }
}
