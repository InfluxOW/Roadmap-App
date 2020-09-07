<?php

namespace App\Models\Users;

use App\Models\User;
use Parental\HasParent;

class Manager extends User
{
    use HasParent;
}
