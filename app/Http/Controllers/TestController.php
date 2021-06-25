<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function testMediaLibrary()
    {
        $path = storage_path('app/public/avatar/test.jpeg');


        $core = imagecreatefromjpeg($path);
        dd($core);
        phpinfo();
    }
    public function runUserFactory()
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

        dd($chat_groups,$users);

//        $chatMessage = \App\Models\ChatMessage::factory()->make();
//        dd($chatMessage);
//        $chatGroup = \App\Models\ChatGroup::factory()
//            ->has(\App\Models\ChatMessage::factory()->count(3), 'chatMessages')
//            ->create();
        dd('runUserFactory', $user);
    }
}
