<?php

use App\Good;
use App\Group;
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

        $user = user::find(1);

        $groupsName = ['Группа 1', 'Группа 2'];

        foreach ($groupsName as $groupName) {
        	$group = Group::create([
        		"name" => $groupName,
        		"code" => strtoupper(substr(md5(time()), 0, 8)),
        		"owner" => $user->id
        	]);
        	$user->groups()->attach($group->id, ['active' => 1]);
        }

        $groups = Group::all();
        $goodNames = ['Кофе в зернах', 'Стакан молока', 'Кофе молотый', 'Чай в пакетиках', 'Печенька', 'Капучино'];
        foreach ($groups as $group) {
        	for ($i=0; $i < 5; $i++) { 
        		Good::create([
        			'icon_id' => $i,
        			'group_id' => $group->id,
        			'name' => $goodNames[array_rand($goodNames, 1)], 
        			'price' => rand(10, 150)
        		]);
        	}
        }
    }
}
