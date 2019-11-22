<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Weibo;

class WeibosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user_ids = ['1','2','3'];
        $faker = app(Faker\Generator::class);

        $weibos = factory(Weibo::class)->times(100)->make()->each(function ($weibo) use ($faker, $user_ids) {
            $weibo->user_id = $faker->randomElement($user_ids);
        });

        Weibo::insert($weibos->toArray());
    }
}
