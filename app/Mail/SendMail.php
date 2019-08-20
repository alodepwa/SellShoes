<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;
    public $user;
    public $product;
    public $path;
    public $message;
    public $size;
    public $price;
    public $soluong;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user,$product,$path,$size,$price,$soluong)
    {
        $this->user=$user;
        $this->product=$product;
        $this->path=$path;
        $this->size=$size;
        $this->price=$price;
        $this->soluong=$soluong;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.order1');
    }
}
