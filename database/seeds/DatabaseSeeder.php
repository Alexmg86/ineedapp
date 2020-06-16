<?php

use App\Good;
use App\Group;
use App\Order;
use App\Payment;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Тестовый пользователь',
            'email' => 'test@mail.ru',
            'password' => Hash::make('123456'),
            'hash' => Str::random(32),
        ]);
        User::create([
            'name' => 'Гость 1',
            'email' => 'test1@mail.ru',
            'password' => Hash::make('123456'),
            'hash' => Str::random(32),
        ]);
        User::create([
            'name' => 'Гость 2',
            'email' => 'test2@mail.ru',
            'password' => Hash::make('123456'),
            'hash' => Str::random(32),
        ]);

        $user = User::find(1);
        $user1 = User::find(2);
        $user2 = User::find(3);

        $groupsName = ['Группа 1', 'Группа 2'];

        foreach ($groupsName as $groupName) {
            $group = Group::create([
                "name" => $groupName,
                "code" => strtoupper(substr(md5(time()), 0, 8)),
                "owner" => $user->id
            ]);
            $user->groups()->attach($group->id, ['active' => 1]);
            $user1->groups()->attach($group->id, ['active' => 1]);
            $user2->groups()->attach($group->id, ['active' => 1]);
        }

        $groups = Group::all();
        $goodNames = ['Кофе в зернах', 'Стакан молока', 'Кофе молотый', 'Чай в пакетиках', 'Печенька', 'Капучино'];
        foreach ($groups as $group) {
            for ($i = 1; $i < 6; $i++) {
                Good::create([
                    'icon_id' => $i,
                    'group_id' => $group->id,
                    'name' => $goodNames[array_rand($goodNames, 1)],
                    'price' => rand(10, 150)
                ]);
            }
        }

        $group = Group::with('goods')->find(2);
        Order::create([
            'user_id' => 3,
            'group_id' => 2,
            'good_id' => $group->goods->first()->id,
            'price' => $group->goods->first()->price
        ]);

        Payment::create([
            'user_id' => 3,
            'group_id' => 2,
            'payment' => 10
        ]);
    }
}
