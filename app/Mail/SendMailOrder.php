<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMailOrder extends Mailable
{
    use Queueable, SerializesModels;
    public $user;
    public $product;
    public $path;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user,$product,$path)
    {
        $this->user=$user;
        $this->product=$product;
        $this->path=$path;
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
