<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Plant;
use App\Models\Tenant\PlantTenantUser;
use App\Models\Tenant\TenantUser;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TenantUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $entries = $request->input('entries', 10);

        $users = TenantUser::when(
            $request->search,
            fn(Builder $query, $search) =>
            $query->whereAny(['id', 'name', 'email'], 'like', "%{$search}%")
        )
            ->latest()
            ->paginate($entries)
            ->withQueryString();

        return inertia('Tenant/Users/Index', [
            'users' => $users,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('tenant_users')
                    ->where(
                        fn(QueryBuilder $query) =>
                        $query->where('tenant_id', tenant('id'))
                    )
            ],
            'password' => ['required', 'string', 'min:8', 'max:20', 'confirmed'],
        ]);

        TenantUser::create($validated);

        return back()->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TenantUser $user)
    {
        return inertia('Tenant/Users/Edit', [
            'user' => $user,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TenantUser $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('tenant_users')
                    ->ignore($user->id)
                    ->where(
                        fn(QueryBuilder $query) =>
                        $query->where('tenant_id', $user->tenant_id)
                            ->where('tenant_id', tenant('id'))
                    )
            ],
            'password' => ['nullable', 'string', 'min:8', 'max:20', 'confirmed'],
        ]);

        if (!$validated['password']) {
            unset($validated['password']);
        }

        $user->update($validated);

        return to_route('users.index', ['tenant' => tenant('id')])
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TenantUser $user)
    {
        $user->delete();

        return back()->with('success', 'User deleted successfully.');
    }
}
