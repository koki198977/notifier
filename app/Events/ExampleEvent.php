<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;


class ExampleEvent implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    protected $message;

    /**
    * Create a new event instance.
    *
    * @return void
    */
    public function __construct($message)
    {
        $this->message = $message;
    }

    /**
    * Get the channels the event should broadcast on.
    *
    * @return Channel|array
    */
    public function broadcastOn()
    {
        // return new PrivateChannel('example');
        return new Channel('example');
    }

    /**
    * Get the data to broadcast.
    *
    * @author Author
    *
    * @return array
    */
    public function broadcastWith()
    {
        return [
            'message' => $this->message,
        ];
    }

    public function broadcastAs(){
        return 'ExampleEvent';
    }
}
