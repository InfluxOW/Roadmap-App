<?php

namespace App\Models\Users;

use App\Models\User;
use Parental\HasParent;

class Developer extends User
{
    use HasParent;
}
