<?php

namespace App\Mail;

use App\Models\Invite;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserInvite extends Mailable
{
    use Queueable;
    use SerializesModels;

    public Invite $invite;

    public function __construct(Invite $invite)
    {
        $this->invite = $invite;
    }

    public function build()
    {
        $app = config('app.name');

        return $this
            ->subject("You were invited to access '{$app}' application")
            ->markdown('emails.invite');
    }
}
