<?php

namespace App\Models\UserTypes;

use App\Models\User;
use Parental\HasParent;

class Admin extends User
{
    use HasParent;
}
