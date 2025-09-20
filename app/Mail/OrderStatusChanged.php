<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderStatusChanged extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $messageText;

    public function __construct(Order $order, string $messageText)
    {
        $this->order = $order;
        $this->messageText = $messageText;
    }

    public function build()
    {
        return $this->subject("Cập nhật đơn hàng #{$this->order->id}")
                    ->view('user.emails.order_status');
    }
}

