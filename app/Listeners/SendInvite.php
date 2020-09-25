<?php

namespace App\Listeners;

use App\Events\InviteCreated;
use App\Jobs\SendMail;
use App\Mail\UserInvite;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendInvite
{
    public function handle(InviteCreated $event)
    {
        SendMail::dispatch($event->invite->email, new UserInvite($event->invite));
    }
}
