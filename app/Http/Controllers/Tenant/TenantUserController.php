<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Plant;
use App\Models\Tenant\PlantTenantUser;
use App\Models\Tenant\TenantUser;
use Illuminate\Database\Eloquent\Builder;
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

        $users = TenantUser::with(['plants'])->when(
            $request->search,
            fn(Builder $query, $search) =>
            $query->whereAny(['id', 'name', 'email'], 'like', "%{$search}%")
        )
            ->latest()
            ->paginate($entries)
            ->withQueryString();

        return inertia('Tenant/Users/Index', [
            'users' => $users,
            'options' => [
                'plants' => Plant::all(['id', 'name'])
                    ->map(fn(Plant $plant) =>
                    [
                        'name' => $plant->name,
                        'value' => $plant->id
                    ]),
            ],
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
                    ->where('tenant_id', tenant('id'))
            ],
            'password' => ['required', 'string', 'min:8', 'max:20', 'confirmed'],
            'plants' => ['nullable', 'array'],
            'plants.*' => [
                'required',
                Rule::exists('plants', 'id')
                    ->withoutTrashed()
                    ->where('tenant_id', tenant('id'))
            ],
        ]);

        $user = TenantUser::create($validated);

        $user->plants()->sync($validated['plants']);

        return back()->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(TenantUser $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TenantUser $user)
    {
        return inertia('Tenant/Users/Edit', [
            'user' => $user->load(['plants']),
            'options' => [
                'plants' => Plant::all(['id', 'name'])
                    ->map(fn(Plant $plant) =>
                    [
                        'name' => $plant->name,
                        'value' => $plant->id,
                    ]),
            ],
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
                    ->where('tenant_id', tenant('id'))
            ],
            'password' => ['nullable', 'string', 'min:8', 'max:20', 'confirmed'],
            'plants' => ['nullable', 'array'],
            'plants.*' => [
                'required',
                Rule::exists('plants', 'id')
                    ->withoutTrashed()
                    ->where('tenant_id', tenant('id'))
            ],
        ]);

        if (!$validated['password']) {
            unset($validated['password']);
        }

        $user->update($validated);

        PlantTenantUser::onlyTrashed()
            ->where('tenant_user_id', $user->id)
            ->whereIn('plant_id', $validated['plants'])
            ->restore();

        $user->plants()->sync($validated['plants']);

        return back()->with('success', 'User updated successfully.');
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
