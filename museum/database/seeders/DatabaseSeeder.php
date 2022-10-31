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
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $users_count = 10;
        $users = collect();
        for ($i = 1; $i <= $users_count; $i++) {
            $users->add(
                \App\Models\User::factory()->create([
                    'email' => 'user' . $i . '@szerveroldali.hu',
                ])
            );
        }
        $users->add(
            \App\Models\User::factory()->create([
                'name' => 'Test User',
                'email' => 'admin@szerveroldali.hu',
                'password' => bcrypt('adminpwd') // TODO: ezt így kéne? idk?
                ])
            );

        $items = \App\Models\Item::factory(rand(5, 10))->create();
        $labels = \App\Models\Label::factory(rand(3, 5))->create();

        $items->each(function ($item) use (&$users, &$labels) {
            $item->labels()->sync(
                $labels->random(rand(1, $labels->count()))
            );
            $item->user()->associate($users->random())->save();
        });

        $comments = \App\Models\Comment::factory(rand(5, 10))->create();
        $comments->each(function ($comment) use (&$users, &$items) {
            $comment->user()->associate($users->random())->save();
            $comment->item()->associate($items->random())->save();
        });
    }
}
