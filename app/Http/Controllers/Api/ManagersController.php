<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\UserTypes\Manager;
use Illuminate\Http\Request;

class ManagersController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewManagers', User::class);

        if ($request->user()->isManager()) {
            return redirect()->route('managers.show', $request->user());
        }
    }

    public function show(Manager $manager)
    {
        $this->authorize('viewManager', $manager);

        return new UserResource($manager);
    }
}
