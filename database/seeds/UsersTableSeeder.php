<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //生成50条数据集合
        $users = factory(User::class)->times(50)->make();
        //makeVisible 方法临时显示 User 模型里指定的隐藏属性 $hidden，然后insert将数据批量插入表内
        User::insert($users->makeVisible(['password', 'remember_token'])->toArray());

        //修改第一个客户的信息，方便登录
        $user = User::find(1);
        $user->name = 'Summer';
        $user->email = 'summer@example.com';
        $user->is_admin = true;
        $user->save();
    }
}
