<?php

namespace App\Http\Controllers;

use App\Enums\Permissions\RolePermissionsEnum;
use App\Enums\Roles\UserRolesEnum;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
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

        $roles = Role::whereNot('name', UserRolesEnum::SuperAdmin)->get();

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
            'name' => ['required', 'string', 'max:255', 'unique:roles,name'],
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
    public function edit(Request $request, Role $role)
    {
        Gate::authorize(RolePermissionsEnum::EditRole);

        Gate::denyIf($role->name === UserRolesEnum::SuperAdmin->value);

        return inertia('Central/Roles/Edit', [
            'role' => $role->load(['permissions']),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        Gate::authorize(RolePermissionsEnum::EditRole);

        Gate::denyIf($role->name === UserRolesEnum::SuperAdmin->value);

        $role->update($request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('roles', 'name')->ignore($role->id)],
        ]));

        return back()->with('success', 'Role updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        Gate::authorize(RolePermissionsEnum::FoceDeleteRole);

        Gate::denyIf($role->name === UserRolesEnum::SuperAdmin->value);

        $role->delete();

        return back()->with('success', 'Role deleted successfully.');
    }

    public function updatePermissions(Request $request, Role $role)
    {
        Gate::authorize(RolePermissionsEnum::EditRole);

        Gate::denyIf($role->name === UserRolesEnum::SuperAdmin->value);

        $request->validate([
            'permissions.*' => ['required', 'string', 'max:255', Rule::exists('permissions', 'name')],
        ]);

        $role->syncPermissions($request->permissions);

        return back()->with('success', 'Permissions synced successfully.');
    }
}
