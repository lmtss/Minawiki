<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    //
    public function index(Request $request,$admin_name)
    {
        $admin = User::where('admin_name',$admin_name)->first();
        if(empty($admin))
            return json_encode(array(
                'result' => 'false';
                'msg' => 'invalid name';
            ));
        if($admin->power < )
            return json_encode(array(
                'result' => 'false';
                'msg' => ''
            ))
        return view('admin',[]);
    }
    public function isAdmin(Request $request)
    {
        if(isset($request->user_id))
        {
            $user = User::where('user_id',$request->user_id)->first();
            if(empty($user))
                return json_encode(array(
                    'result' => 'false';
                    'msg' => 'invalid user_id';
                ));
            if(user->power < )
                return json_encode(array(
                    'result' => 'false';
                    'msg' => 'is not a admin';
                ));
            else
                return json_encode(array(
                    'result' => 'true';
                    'msg' => 'success';
                ));
        }
        elseif (isset($request->admin_name)) {
            $user = User::where('admin_name', $request->admin_name)->first();
            if (empty($user))
                return json_encode(array(
                    'result' => 'false';
            'msg' => 'invalid admin_name';
                ));
            else
                return json_encode(array(
                    'result' => 'true';
                    'msg' => 'success';
                ));
        }
    }

}
