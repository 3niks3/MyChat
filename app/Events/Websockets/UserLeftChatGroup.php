<?php

namespace App\Events\Websockets;

use App\Models\ChatGroup;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserLeftChatGroup implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user, $chat_group;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(ChatGroup $chat_group, User $user)
    {
        $this->user = $user;
        $this->chat_group = $chat_group;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PresenceChannel('chat-room.'.$this->chat_group->id);
    }

    public function broadcastWith()
    {
        return [
            'user_id' => $this->user->id,
            'username' => $this->user->username,
        ];
    }

    public function broadcastAs()
    {
        return 'chat_member_left';
    }
}
