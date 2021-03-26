<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DeletePaymentEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $total_data;

    public $total_delete;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($total_data,$total_delete)
    {
        $this->total_data   = $total_data;
        $this->total_delete = $total_delete;
        $this->message      = "Proccesing delete {$total_delete} of {$total_data}";
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return ['proccess-delete'];
    }
}
