<?php

namespace App\Events\Websockets;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotyAllert implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $user = null;
    public $message = '';
    public $type = '';

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($mesasge, $type = 'info')
    {
        $this->user = auth('web')->user();
        $this->message = $mesasge;
        $this->type = $type;
    }

    public function broadcastWith()
    {
        return [
            'type' => $this->type,
            'message' => $this->message,
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('notifications.'.$this->user->id);
    }



    public function broadcastAs()
    {
        return 'noty';
    }
}
