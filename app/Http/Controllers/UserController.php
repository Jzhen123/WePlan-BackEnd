<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserController extends Controller
{
    public function index(Request $request)
    {
        return $request->user();
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'nullable|email|max:64',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response(['message' => 'Validation errors', 'errors' =>$validator->errors(), 'status' => false], 422);
        }

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $user = User::create($input);

        // User auth access token is generated below
        $data['token'] = $user->createToken('WePlan')->accessToken;

        $data['user_data'] = $user;

        return response(['data' => $data, 'message' => 'Account created successfully!', 'status' => true]);
    }

    public function logout()
    {
        if (Auth::check()) {
            Auth::user()->token()->revoke();
            return response()->json(['success' => 'logout_sucess'], 200);
        } else {
            return response()->json(['error' => 'api.something_went_wrong'], 500);
        }
    }
}
