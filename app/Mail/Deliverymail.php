<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Purchaseorder;

class Deliverymail extends Mailable
{
    use Queueable, SerializesModels;
	
	public $purchaseorder;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Purchaseorder $purchaseorder)
    {
        $this->purchaseorder = $purchaseorder;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
		$path = str_replace('\\', '/', storage_path()) . '/app/delivery/' . date('Y') . '/' . date('m');
        return $this->view('emails.delivery');
    }
}
