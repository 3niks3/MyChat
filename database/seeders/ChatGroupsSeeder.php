<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ChatGroupsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $chat_groups = \App\Models\ChatGroup::factory()
            ->count(20)
            ->has(\App\Models\ChatMessage::factory()->count(50), 'chatMessages')
            ->create();

        $users = \App\Models\User::all();

        foreach($users as $user)
        {
            foreach($chat_groups as $chat_group)
            {
                switch(true)
                {
                    case($user->id == 1):
                        $role= 'admin';
                        break;
                    case($user->id == 2 || $user->id == 3):
                        $role= 'mod';
                        break;
                    default:
                        $role= 'member';
                        break;
                }

                \App\Models\UserHasChatGroup::create([
                    'user_id' => $user->id,
                    'chat_group_id' => $chat_group->id,
                    'role' => $role,
                ]);
            }
        }

    }
}
