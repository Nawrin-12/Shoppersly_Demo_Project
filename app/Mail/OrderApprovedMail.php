<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class OrderApprovedMail extends Mailable
{
    public $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function build()
{
    return $this->subject('Your Order Has Been Approved')
                ->html("Hello {$this->order->customer_name},<br><br>Your order for \"{$this->order->product_details}\" has been approved.<br><br>Thank you!");
}

}
