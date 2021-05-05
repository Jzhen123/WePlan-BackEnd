<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\User;

class GroupController extends Controller
{
    // public function index () {

    // }

    public function create(Request $request)
    {
        $user = User::find($request->created_by_user_id);

        if ($user) {
            $groupName = Group::find($request->name);

            if ($groupName) {
                return response()->json(['error' => 'There is already a group with this name! Please try another name.'], 422);
            } else {
                $input = $request->all();
                $group = Group::create($input);

                return response()->json(['data' => $group, 'message' => 'Group created successfully!'], 200);
            }


        } else if (!$user) {
            return response()->json(['error' => 'You do not have access to make a group. Create an account first and then try again!'], 422);
        }
    }

    // public function update () {

    // }

    // public function delete () {

    // }

}
