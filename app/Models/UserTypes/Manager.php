<?php

namespace App\Models\UserTypes;

use App\Models\User;
use Parental\HasParent;

class Manager extends User
{
    use HasParent;
}
