<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Germey\Geetest\GeetestCaptcha;


class AuthController extends Controller
{
    use GeetestCaptcha;

    /**
     * @param Request $request
     * @return view
     */
    public function showRegisterView(Request $request)
    {
        return view('auth.register');
    }

    /**
     * @param Request $request
     * @return view
     */
    public function showLoginView(Request $request)
    {
        return view('auth.login');
    }

    /**
     * @param Request $request
     * @return bool
     */
    private function geetestValidate(Request $request)
    {
        $this->validate($request, [
            'geetest_challenge' => 'geetest',
        ], [
            'geetest' => config('geetest.server_fail_alert')
        ]);

        return true;
    }

    /**
     * @param Request $request
     * @return string(json)
     */
    public function sendTextCaptcha(Request $request)
    {
        //Test Geetest status
        if (!$this->geetestValidate($request))
            return json_encode(array('result' => 'false', 'msg' => 'geetest invalid'));
        //Check if tel exist
        if (User::where('tel', $request->tel)->count() > 0)
            return json_encode(array('result' => 'false', 'msg' => 'telephone already exists'));
        //Generate random captcha
        $captcha = "";
        for ($i = 0; $i < 6; ++$i)
            $captcha .= strval(rand(1, 9));
        //Save info to session
        $request->session()->put('captcha.tel', $request->tel);
        $request->session()->put('captcha.text', $captcha);
        $request->session()->put('captcha.timestamp', time());
        //Post to Yunpian api
        $ch = curl_init();

        $apikey = env('YUNPIAN_KEY');
        $mobile = $request->tel;
        $text = "【东大水站】您的验证码是" . $captcha;

        $data = array('text' => $text, 'apikey' => $apikey, 'mobile' => $mobile);
        curl_setopt($ch, CURLOPT_URL, 'https://sms.yunpian.com/v2/sms/single_send.json');
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        return strval(curl_exec($ch));
    }

    /**
     * @param Request $request
     */
    public function addUser(Request $request)
    {
        if (env('APP_ENV') != 'testing')
            $this->validate($request, [
                'tel' => 'required',
                'password' => 'required',
                'captcha' => 'required'
            ]);
        else
            $this->validate($request, [
                'tel' => 'required',
                'password' => 'required'
            ]);
        //Check if tel distorted
        if ($request->tel != $request->session()->get('captcha.tel'))
            return json_encode(array('result' => 'false', 'msg' => 'distorted telephone'));
        if (env('APP_ENV') != 'testing') {
            //Validate text captcha
            if (!isset($request->captcha) || !$request->session()->has('captcha.text') ||
                !$request->captcha == $request->session()->get('captcha.text')
            )
                return json_encode(array('result' => 'false', 'msg' => 'invalid captcha'));
            if (!$request->session()->has('captcha.timestamp') ||
                time() - intval($request->session()->get('captcha.timestamp')) > 5 * 60
            )
                return json_encode(array('result' => 'false', 'msg' => 'expired captcha'));
        }
        //Check if tel exist
        if (User::where('tel', $request->tel)->count() > 0)
            return json_encode(array('result' => 'false', 'msg' => 'telephone already exists'));
        //Generate random salt
        $salt = base64_encode(random_bytes(24));
        //Construct new user
        $user = new User;
        $user->tel = $request->tel;
        $user->password = Hash::make($salt . $request->password);
        $user->salt = $salt;
        $user->disable = false;
        $user->active_point = 0;
        $user->contribute_point = 0;
        $user->power = 0;
        $user->admin_name = "";
        $user->theme = "yayin";
        $user->no_disturb = false;
        $user->token = "";
        //Save new user
        $user->save();
        $request->session()->put('user_id', $user->id);
        $request->session()->put('user_tel', $user->tel);
        $request->session()->put('user_theme', $user->theme);
        $request->session()->put('user_power', $user->power);
        $request->session()->put('user_admin', $user->admin_name);
        return json_encode(array('result' => 'true', 'msg' => 'success'));
    }

    /**
     * @param Request $request
     */
    public function login(Request $request)
    {
        $this->validate($request, [
            'tel' => 'required',
            'password' => 'required'
        ]);
        if (User::where('tel', $request->tel)->count() == 0)
            return json_encode(array('result' => 'false', 'msg' => 'wrong'));
        $user = User::where('tel', $request->tel)->first();
        if (Hash::check($user->salt . $request->password, $user->password)) {
            //Generate and save token
            $user->token = $user->tel . strval(time());
            $user->save();
            $request->session()->put('user_id', $user->id);
            $request->session()->put('user_tel', $user->tel);
            $request->session()->put('user_theme', $user->theme);
            $request->session()->put('user_power', $user->power);
            $request->session()->put('user_admin', $user->admin_name);
            return response(json_encode(array('result' => 'true', 'msg' => 'success')))
                ->cookie('token', $user->token, 2 * 30 * 24 * 60);
        } else
            return json_encode(array('result' => 'false', 'msg' => 'wrong'));
    }
}