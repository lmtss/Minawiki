<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\SystemMessage;
use Parsedown;

class SystemMessageController extends Controller
{


    public function store(Request $request)
    {
        $receiver = User::where('id',$request->id)->first();
        if(empty($receiver))
            return json_encode(array(
                'result' => 'false',
                'msg' => 'invalid user_id'
            ));
        $sysMessage = new SystemMessage();
        $sysMessage->behavior = $request->behavior;
        $sysMessage->user_id = $request->id;
        $sysMessage->comment = $request->comment;
        $sysMessage->admin_name = $request->session()->get('user.admin_name');
        $sysMessage->save();
        return json_encode(array(
            'result' => 'true',
            'msg' => 'success'
        ));
    }



}
