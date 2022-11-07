<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //this deletes all tables and records if they exist
        //$this->command->call('migrate:refresh');

        $users_count = \App\Models\User::count();

        if ($users_count == 0) {
            $users = collect();
            $users->add(
                \App\Models\User::factory()->create([
                    'name' => 'admin',
                    'email' => 'admin@szerveroldali.hu',
                    'password' => bcrypt('adminpwd'),
                    'is_admin' => true,
                ])
            );
            $users_count = 1;
        } else {
            $users = \App\Models\User::all();
        }

        for ($i = $users_count; $i <= $users_count+10 ; $i++) {
            $users->add(
                \App\Models\User::factory()->create([
                    'email' => 'user' . $i . '@szerveroldali.hu',
                    'password' => bcrypt('password'),
                ])
            );
        }

        $users_count = \App\Models\User::count();


        \App\Models\Item::factory(rand(10, 15))->create();
        \App\Models\Label::factory(rand(3, 5))->create();

        $items = \App\Models\Item::all();
        $labels = \App\Models\Label::all();

        $items->each(function ($item) use (&$users, &$labels) {
            $item->labels()->sync(
                $labels->random(rand(1, $labels->count()))
            );
            $item->user()->associate($users->random())->save();
        });

        $comments = \App\Models\Comment::factory(rand(10,15))->create();
        $comments->each(function ($comment) use (&$users, &$items) {
            $comment->user()->associate($users->random())->save();
            $comment->item()->associate($items->random())->save();
        });
    }
}
