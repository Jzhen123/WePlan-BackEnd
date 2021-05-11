<?php

namespace App\Http\Controllers;
use App\Models\Invite;
use App\Models\User;
use App\Models\Group_user;
use App\Models\Group;
use App\Mail\InviteCreated;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;


use Illuminate\Http\Request;

class InviteController extends Controller
{
    public function invite()
    {
        return view('invite');
    }

    public function process(Request $request)
    {
        // validate request
        do {
            $token = Str::random(16);
        }
        while (Invite::where('token', $token)->first());

        $user = User::where('email', $request->email)->first();
        // return $user->id;
        $invite = Invite::create([
            'user_id' => $request->user_id,
            'group_id' => $request->group_id,
            'receiving_user_id' => $user->id,
            'token' => $token,
        ]);

        $result = Mail::to($user->email)->send(new InviteCreated($invite));
        return response()->json(['data' => $invite, "message" => "Invited " . $user->name . " to the group!"], 200);
    }

    public function accept($token)
    {
        if (!$invite = Invite::where('token', $token)->first()) {
            return response()->json(["message" => "Invite was not found!"], 422);
        }

        $user = User::where('id', $invite->receiving_user_id)->first();
        $group = Group::where('id', $invite->group_id)->first();
        Group_user::create(['user_id' => $user->id, 'group_id' => $invite->group_id]);

        $invite->delete();

        return "Congrats! You have successfully joined $group->name. Head over to https://awesome-weplan.web.app and start planning!";
    }
}
