<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Support\Facades\Auth;
use app\User;

class PrivateChat implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $roomId;
    public $body;
    public $message;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($roomId, $message, $session)
    {
        $this->roomId = $roomId;
        // $this->message = $session;
        $this->message = $message;
      // return false;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */


    public function broadcastOn()
    {
        return new PresenceChannel('privateChat.' . $this->roomId); //@@@ надо private channel kakto
    }

}
