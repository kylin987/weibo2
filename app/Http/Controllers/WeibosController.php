<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\Weibo;

class WeibosController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    //发布微博
    public function store(Request $request){
        $this->validate($request,[
            'content' =>'required|max:140'
        ]);

        Auth::user()->weibos()->create([
            'content' => $request['content']
        ]);
        session()->flash('success','发布成功');
        return redirect()->back();
    }
    //删除微博
    public function destroy(Weibo $weibo){
        $this->authorize('destroy', $weibo);
        $weibo->delete();
        session()->flash('success','删除成功');
        return redirect()->back();
    }
}
