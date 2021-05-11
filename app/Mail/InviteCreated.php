<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Invite;
use App\Http\Controllers\InviteController;

class InviteCreated extends Mailable
{
    use Queueable, SerializesModels;
    public $invite;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Invite $invite)
    {
        $this->invite = $invite;
    }

    // public function accept(){
    //     return $this->
    // }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('awesome.weplan@gmail.com')
                    ->view('emails.invite');
    }
}
