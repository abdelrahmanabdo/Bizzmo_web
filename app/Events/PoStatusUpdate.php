<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use App\Purchaseorder;

class PoStatusUpdate implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $purchaseOrder;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Purchaseorder $purchaseOrder)
    {
        $this->purchaseOrder = $purchaseOrder;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('po.' . $this->purchaseOrder->id);
    }

    public function broadcastAs()
    {
        return 'po.status.update';
    }

    public function broadcastWith()
    {
        switch ($this->purchaseOrder->status->id) {
            case 7:
                $message = "Your purchase order has been approved by credit. It is now pending supplier approval.";
                $messageType = "success";
                break;
            case 14:
                $message = "Your purchase order has been rejected by credit.";
                $messageType = "danger";
                break;
            
            default:
                $message = "Your purchase order has been updated";
                $messageType = "info";
                break;
        }
        

        return [
            'id' => $this->purchaseOrder->id,
            'newStatus' => $this->purchaseOrder->status->name,
            'messageType' => $messageType,
            'message' => $message
        ];
    }
}
