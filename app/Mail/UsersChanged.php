<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UsersChanged extends Mailable
{
    use Queueable, SerializesModels;

    public array $titles;
    public bool $userChanged;
    public bool $rolesChanged;
    public bool $clientsChanged;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(?array $titles, bool $userChanged, bool $rolesChanged, bool $clientsChanged)
    {
        $this->titles = $titles;
        $this->userChanged= $userChanged;
        $this->rolesChanged = $rolesChanged;
        $this->clientsChanged = $clientsChanged;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.users.changed')
            ->subject(env('APP_NAME') . ' - изменены данные пользователя')
            ;
    }
}
