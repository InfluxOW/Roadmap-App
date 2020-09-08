<?php

namespace App\Models\Users;

use App\Models\User;
use Parental\HasParent;

class Employee extends User
{
    use HasParent;
}
