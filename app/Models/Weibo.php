<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Weibo extends Model
{
    //设置可以被正常更新的字段
    protected $fillable = ['content'];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
