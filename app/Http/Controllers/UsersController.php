<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use Mail;

class UsersController extends Controller
{

    public function __construct(){
        //除了下面3个动作，其他动作都需要登录才能访问
        $this->middleware('auth', [
            'except' =>['show','create','store','index','confirmEmail']
        ]);

        //这句的意思，应该是，注册页面只能未登录用户访问，而不是未登录用户只能访问注册页面
        $this->middleware('guest',[
            'only' =>['create']
        ]);
    }

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

        //Auth::login($user);
        $this->sendEmailConfirmationTo($user);
        session()->flash('success','注册成功，请检查邮箱中的注册邮件进行账号激活。');

        return redirect('/');
    }

    //展示用户资料修改的页面
    public function edit(User $user){
        $this->authorize('update', $user);
        return view('users.edit',compact('user'));
    }

    //提交保存用户修改的内容
    public function update(User $user,Request $request){
        $this->authorize('update', $user);

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

    //列出所有的用户
    public function index(){
        $users = User::paginate(10);
        return view('users.index',compact('users'));
    }

    //删除用户
    public function destroy(User $user)
    {
        $this->authorize('destroy', $user);
        $user->delete();
        session()->flash('success', '成功删除用户！');
        return back();
    }

    //发送邮件
    protected function sendEmailConfirmationTo($user)
    {
        $view = 'emails.confirm';
        $data = compact('user');
        $to = $user->email;
        $subject = "感谢注册 Weibo 应用！请确认你的邮箱。";

        Mail::send($view, $data, function ($message) use ($to, $subject) {
            $message->to($to)->subject($subject);
        });
    }
    //验证邮件
    public function confirmEmail($token)
    {
        $user = User::where('activation_token', $token)->firstOrFail();

        $user->activated = true;
        $user->activation_token = null;
        $user->save();

        Auth::login($user);
        session()->flash('success', '恭喜你，激活成功！');
        return redirect()->route('users.show', [$user]);
    }
}
