<?php

namespace App\Http\Controllers;

use App\Enums\ActivityLogs\LogNamesEnum;
use App\Enums\Permissions\UserPermissionsEnum;
use App\Enums\Roles\UserRolesEnum;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Spatie\Activitylog\Models\Activity;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize(UserPermissionsEnum::ViewUser);

        $users = User::with('roles')->withoutRole(UserRolesEnum::SuperAdmin)->orderByDesc('id')->get();

        return inertia('Central/Users/Index', [
            'users' => $users,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize(UserPermissionsEnum::CreateUser);

        return inertia('Central/Users/Create', [
            'roles' => Role::whereNot('name', UserRolesEnum::SuperAdmin)->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize(UserPermissionsEnum::CreateUser);

        $user = User::create(
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
                'password' => ['required', 'min:8', 'max:255', 'confirmed'],
                'roles.*' => ['required', Rule::exists('roles', 'name')],
            ]),
        );

        $user->syncRoles($request->roles);

        return back()->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        Gate::authorize(UserPermissionsEnum::ViewUser);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        Gate::authorize(UserPermissionsEnum::EditUser);

        Gate::denyIf($user->hasRole(UserRolesEnum::SuperAdmin));

        return inertia('Central/Users/Edit', [
            'user' => $user->load(['roles']),
            'roles' => Role::whereNot('name', UserRolesEnum::SuperAdmin)->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        Gate::authorize(UserPermissionsEnum::EditUser);

        Gate::denyIf($user->hasRole(UserRolesEnum::SuperAdmin));

        $user->update(
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
                'roles.*' => ['required', Rule::exists('roles', 'name')],
            ]),
        );

        $user->syncRoles($request->roles);

        return back()->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        Gate::authorize(UserPermissionsEnum::DeleteUser);

        Gate::denyIf($user->hasRole(UserRolesEnum::SuperAdmin));

        $user->delete();

        return back()->with('success', 'User deleted successfully.');
    }

    public function trashed()
    {
        Gate::authorize(UserPermissionsEnum::ViewTrashedUser);

        $users = User::onlyTrashed()->with('roles')->withoutRole(UserRolesEnum::SuperAdmin)->get();

        return inertia('Central/Users/Trashed', [
            'users' => $users,
        ]);
    }

    public function restore(Request $request)
    {
        Gate::authorize(UserPermissionsEnum::RestoreUser);

        $request->validate([
            'id' => ['required', Rule::exists('users', 'id')],
        ]);

        $user = User::onlyTrashed()->where('id', $request->id)->first();

        Gate::denyIf($user->hasRole(UserRolesEnum::SuperAdmin));

        $user->restore();

        return back()->with('success', 'User restored successfully.');
    }

    public function forceDelete(Request $request)
    {
        Gate::authorize(UserPermissionsEnum::ForceDeleteUser);

        $request->validate([
            'id' => ['required', Rule::exists('users', 'id')],
        ]);

        $user = User::onlyTrashed()->where('id', $request->id)->first();

        Gate::denyIf($user->hasRole(UserRolesEnum::SuperAdmin));

        $user->forceDelete();

        return back()->with('success', 'User permanent deleted successfully.');
    }

    public function audits()
    {
        $audits = Activity::with(['causer'])
            ->where('log_name', LogNamesEnum::User->value)
            ->orderByDesc('id')
            ->get();

        return inertia('Central/Users/Audit', [
            'audits' => $audits,
        ]);
    }
}
