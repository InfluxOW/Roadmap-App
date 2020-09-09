<?php

namespace App\Models\UserTypes;

use App\Models\User;
use Parental\HasParent;

class Employee extends User
{
    use HasParent;
}
