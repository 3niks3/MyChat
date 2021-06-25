<?php

namespace App\Events\Websockets;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\ChatMessage;

class SendChatMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $chat_message;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(ChatMessage $chat_message)
    {
        $this->chat_message = $chat_message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PresenceChannel('chat-room.'.$this->chat_message->chat_group_id);
    }

    public function broadcastWith()
    {
        return [
            'chat_message' => $this->chat_message->createMessageHtml('other'),
        ];
    }

    public function broadcastAs()
    {
        return 'send_message';
    }
}
