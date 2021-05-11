<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Group_user;


class UserController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        foreach($user->groups as $group) {
            $groupUsers = Group_user::where('group_id', $group->id)->get();
            foreach($groupUsers as $member) {
                $name = User::find($member->user_id)->name;
                $members["$member->user_id"] = $name;
            }
            $group["members"] = $members;
        }

        // Do the same with group events
        return $user;
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:64|unique:users,email,',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response(['message' => 'Validation errors', 'errors' => $validator->errors(), 'status' => false], 422);
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
