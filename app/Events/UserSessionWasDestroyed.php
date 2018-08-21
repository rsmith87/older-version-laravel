<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class UserSessionWasDestroyed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $sessionId;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user, $sessionId)
    {
        $this->user = $user;
        $this->sessionId = $sessionId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }

    public function broadcastWith()
    {
        return ['user_id' => $this->user->id];
    }
}
