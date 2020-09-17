<?php

namespace App\Repositories\Dashboards;

use App\Models\UserTypes\Manager;
use Illuminate\Http\Request;

class ManagerDashboardRepository
{
    private const WITH = [

    ];

    public function index(Request $request)
    {
        return Manager::whereUsername($request->route('manager'))->with(self::WITH)->firstOrFail();
    }
}
