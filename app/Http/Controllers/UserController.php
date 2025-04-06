<?php

namespace App\Http\Controllers;

use App\Enums\Permissions\UserPermissionsEnum;
use App\Enums\Roles\UserRolesEnum;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize(UserPermissionsEnum::ViewUser);

        $users = User::orderByDesc('id')->get();

        return inertia('Central/Users/Index', [
            'users' => $users
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize(UserPermissionsEnum::CreateUser);

        return inertia('Central/Users/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize(UserPermissionsEnum::CreateUser);

        User::create($request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|min:8|max:255|confirmed',
        ]))->assignRole(UserRolesEnum::User);

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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        Gate::authorize(UserPermissionsEnum::UpdateUser);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        Gate::authorize(UserPermissionsEnum::DeleteUser);

        $user->delete();

        return back()->with('success', 'User deleted successfully.');
    }
}
