<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\UserTypes\Manager;
use Illuminate\Http\Request;

class ManagersController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class, 'manager');
    }

    protected function resourceAbilityMap()
    {
        return [
            'index' => 'viewManagers',
            'show' => 'viewManager',
        ];
    }

    protected function resourceMethodsWithoutModels()
    {
        return ['index'];
    }

    public function index(Request $request)
    {
        if ($request->user()->isManager()) {
            return redirect()->route('managers.show', $request->user());
        }

        // TODO: admin logic for viewing managers
    }

    public function show(Manager $manager)
    {
        return new UserResource($manager);
    }
}
