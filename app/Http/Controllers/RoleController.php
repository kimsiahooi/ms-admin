<?php

namespace App\Http\Controllers;

use App\Enums\Permissions\RolePermissionsEnum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize(RolePermissionsEnum::ViewRole);

        $roles = Role::all();
        return inertia('Central/Roles/Index', [
            'roles' => $roles,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize(RolePermissionsEnum::CreateRole);

        return inertia('Central/Roles/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize(RolePermissionsEnum::CreateRole);

        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name'
        ]);

        Role::create([
            'name' => $request->name,
        ]);

        return back()->with('success', 'Role created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        Gate::authorize(RolePermissionsEnum::ViewRole);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        Gate::authorize(RolePermissionsEnum::UpdateRole);

        $permissions = $role->getAllPermissions();

        return inertia('Central/Roles/Edit', [
            'role' => $role,
            'permissions' => $permissions,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        Gate::authorize(RolePermissionsEnum::UpdateRole);

        $role->update($request->validate([
            'name' => 'required|string|max:255|unique:roles,name'
        ]));

        return back()->with('success', 'Role updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        Gate::authorize(RolePermissionsEnum::FoceDeleteRole);

        $role->delete();

        return back()->with('success', 'Role deleted successfully.');
    }
}
