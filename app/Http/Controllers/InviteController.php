<?php

namespace App\Http\Controllers;
use App\Models\Invite;
use App\Models\User;
use App\Models\Group_user;
use App\Mail\InviteCreated;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;


use Illuminate\Http\Request;

class InviteController extends Controller
{
    // public function invite()
    // {
    //     // show the user a form with an email field to invite a new user
    // }

    public function process(Request $request)
    {
        // validate request
        do {
            $token = Str::random(16);
        }
        while (Invite::where('token', $token)->first());

        $user = User::where('id', $request->receiving_user_id);

        $invite = Invite::create([
            'user_id' => $request->user_id,
            'group_id' => $request->group_id,
            'email' => $user->email,
            'token' => $token,
        ]);

        Mail::to($user->email)->send(new InviteCreated($invite));
        return response()->json(['data' => $invite, "message" => "Invited $user->name to the group!"], 200);
    }

    public function accept($token)
    {
        if ($invite = Invite::where('token', $token)->first()) {
            return response()->json(["message" => "Invite was not found!"], 422);
        }

        $user = User::where('email', $invite->email);

        Group_user::create(['user_id' => $user->id, 'group_id' => $invite->group_id]);

        $invite->delete();

        return response()->json(["message" => "You have joined $invite->group_id! Start planning at www.awesome-weplan.web.app"], 200);
    }
}
