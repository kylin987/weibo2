<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends Controller
{

    //显示用户注册页面
    public function create(){
        return view('users.create');
    }

    //显示用户信息页面
    public function show(User $user){
        return view('users.show',compact('user'));
    }


    //存储提交的注册信息
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:50',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|confirmed|min:6'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        Auth::login($user);
        session()->flash('success','欢迎，您将在这里开启一段新的旅程~');

        return redirect()->route('users.show',[$user]);
    }

    //展示用户资料修改的页面
    public function edit(User $user){
        return view('users.edit',compact('user'));
    }

    //提交保存用户修改的内容
    public function update(User $user,Request $request){
        $this->validate($request,[
            'name' => 'required|max:50',
            'password' => 'nullable|confirmed|min:6'
        ]);

        $data = [];
        $data['name'] = $request->name;
        if($request->password){
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        session()->flash('success','用户资料修改成功');

        return redirect()->route('users.show', $user);
    }
}
