<?php

namespace App\Models\Users;

use App\Models\User;
use Parental\HasParent;

class Admin extends User
{
    use HasParent;
}
