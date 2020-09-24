<?php

namespace App\Events;

use App\Models\Invite;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InviteCreated
{
    use Dispatchable;
    use SerializesModels;

    public Invite $invite;

    public function __construct(Invite $invite)
    {
        $this->invite = $invite;
    }
}
