<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\User;
use App\Models\Group_user;

class GroupController extends Controller
{
    public function index () {
        return Group::All();
    }

    public function show($id) {
        return Group::find($id);
    }

    public function create(Request $request)
    {
        $user = User::find($request->created_by_user_id); // Check if person creating the group is an existing user

        if ($user) { // If they are a user
            $groupName = Group::where('name', $request->name)->first(); // Check is group name is already in use

            if ($groupName === NULL) { // If not, make the new group
                $input = $request->all();
                $group = Group::create($input);
                Group_user::create(['user_id' => $request->created_by_user_id, 'group_id' => $group->id]);

                return response()->json(['data' => $group, 'message' => 'Group created successfully!'], 200);

            } else if ($groupName->name === $request->name) { // If there is, Return error below
                return response()->json(['error' => 'There is already a group with this name! Please try another name.'], 422);
            }
        } else if (!$user) { // If they arent a user, tell them to sign up first
            return response()->json(['error' => 'You do not have access to make a group. Create an account first and then try again!'], 422);
        }
    }

    // public function update () {

    // }

    public function delete(Request $request)
    {
        $name = Group::find($request->id)->name;

        Group::find($request->id)->delete();

        return response()->json(['message' => "The Group: " . "'$name'" . " has been deleted successfully!"], 200);
    }
}
