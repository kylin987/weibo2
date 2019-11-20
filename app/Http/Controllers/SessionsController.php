<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class SessionsController extends Controller
{
    //展示登陆页面
    public function create(){
        return view('sessions.create');
    }

    //登陆提交
    public function store(Request $request){
        $credentials = $this->validate($request,[
            'email' =>'required|email|max:255',
            'password' => 'required'
        ]);

        if(Auth::attempt($credentials)){
            //登陆成功
            session()->flash('success','欢迎回来');
            return redirect()->route('users.show',[Auth::user()]);
        }else{
            //登陆不成功
            session()->flash('danger','很抱歉，您的邮箱和密码不匹配');
            return redirect()->back()->withInput();
        }

        return;
    }
}
