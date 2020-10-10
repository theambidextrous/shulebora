<?php

namespace App\Mail;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AccountCreation extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $raw_password;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, $raw_password)
    {
        $this->user = $user;
        $this->raw_password = $raw_password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Account Creation | ShuleBora Digital')->view('mails.account_creation');
    }
}
