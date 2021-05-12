<?php
use App\Models\Group;
use App\Models\User;

$group = Group::where('id', $invite->group_id)->first();
$user = User::where('id', $invite->user_id)->first();
?>
<p>Hey there, it's Jimmy from WePlan!</p>

<p>You have been invited to join {{$group->name}} by {{$user->name}}</p>
<p>Paste the link below to join the group!</p>
 <p>https://we-plan-jiayuzheng01421007.codeanyapp.com/api/group/accept/{{$invite->token}}</p>
