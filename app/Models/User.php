<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    //监听用户创建creating,并添加activation_token
    public static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->activation_token = Str::random(10);
        });
    }

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function gravatar($size = '100'){
        $hash = md5(strtolower(trim($this->attributes['email'])));
        return "http://www.gravatar.com/avatar/$hash?s=$size";
    }
    //与微博绑定一对多的关系
    public function weibos(){
        return $this->hasMany(Weibo::class);
    }

    public function feed(){
        return $this->weibos()->orderBy('created_at','desc');
    }
    //获取粉丝关系列表（多对多关系）
    public function followers(){
        return $this->belongsTomany(User::class,'followers','user_id','follower_id');
    }
    //获取用户关注的人的列表（多对多关系）
    public function followings(){
        return $this->belongsTomany(User::class,'followings','follower_id','user_id');
    }
    //关注操作，$user_ids 为要去关注的人
    public function follow($user_ids){
        if(!is_array($user_ids)){
            $user_ids = compact('user_ids');
        }
        $this->followings()->sync($user_ids,false);
    }

    //取消关注操作，$user_ids 为要去取消关注的人
    public function unfollow($user_ids){
        if(!is_array($user_ids)){
            $user_ids = compact('user_ids');
        }
        $this->followings()->detach($user_ids);
    }

    public function isFollowing($user_id){
        return $this->followings->contains($user_id);
    }
}
