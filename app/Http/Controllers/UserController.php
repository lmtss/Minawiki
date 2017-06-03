<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function allUser(Request $request)
    {
        $allUser = User::all();
        $user_id = array();
        foreach ($allUser as $user)
        {
            array_push($user_id,$user->id);
        }
        return json_encode($user_id);
    }

}
