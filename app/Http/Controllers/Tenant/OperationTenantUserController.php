<?php

namespace App\Http\Controllers\Tenant;

use App\Enums\Tenant\Plant\Operation\TenantUser\Status;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Operation;
use App\Models\Tenant\Plant;
use App\Models\Tenant\TenantUser;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OperationTenantUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Plant $plant, Operation $operation)
    {
        $entries = $request->input('entries', 10);

        $users = $operation->users()
            ->when(
                $request->search,
                fn(Builder $query, $search) =>
                $query->whereAny(
                    ['tenant_users.id', 'tenant_users.name', 'tenant_users.email'],
                    'like',
                    "%{$search}%"
                )
            )
            ->when(
                $request->status,
                fn(Builder $query, $status) =>
                $query->whereHas(
                    'operations',
                    fn() => $query->whereIn('status', $status)
                )
            )
            ->orderByPivot('created_at', 'desc')
            ->paginate($entries)
            ->withQueryString();

        return inertia('Tenant/Plants/Operations/TenantUsers/Index', [
            'plant' => $plant,
            'operation' => $operation,
            'users' => $users,
            'options' => [
                'select' => [
                    'statuses' => Status::selectOptions(),
                    'users' => TenantUser::all(['id', 'name'])
                        ->map(fn(TenantUser $user) => [
                            'name' => $user->name,
                            'value' => $user->id,
                        ])
                ],
                'switch' => [
                    'statuses' => Status::switchOptions(),
                ],
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, Plant $plant, Operation $operation)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Plant $plant, Operation $operation)
    {
        $validated = $request->validate([
            'users' => ['required', 'array'],
            'users.*' => [
                'required',
                Rule::exists('tenant_users', 'id')
                    ->withoutTrashed()
                    ->where('tenant_id', $plant->id)
                    ->where('tenant_id', $operation->id)
                    ->where('tenant_id', tenant('id')),
                Rule::unique('operation_tenant_user', 'tuser_id')
                    ->where('operation_id', $operation->id)
                    ->where('tenant_id', $plant->id)
                    ->where('tenant_id', $operation->id)
                    ->where('tenant_id', tenant('id'))
            ],
            'status' => ['required', 'boolean'],
        ]);

        $validated['status'] = Status::toggleStatus($validated['status']);

        $users = collect($validated['users'])
            ->mapWithKeys(fn(string $userId) => [
                $userId => [
                    'status' => $validated['status'],
                ]
            ]);

        $operation->users()->syncWithoutDetaching($users);

        return back()->with('success', 'Users attached successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Plant $plant, Operation $operation, TenantUser $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Plant $plant, Operation $operation, TenantUser $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Plant $plant, Operation $operation, TenantUser $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Plant $plant, Operation $operation, TenantUser $user)
    {
        $operation->users()->toggle($user);

        return back()->with('success', 'User detached successfully.');
    }

    public function toggleStatus(Request $request, Plant $plant, Operation $operation, TenantUser $user)
    {
        $validated = $request->validate([
            'status' => ['required', 'boolean'],
        ]);

        $validated['status'] = Status::toggleStatus($validated['status']);

        $operation->users()->updateExistingPivot($user, $validated);

        return back()->with('success', 'User status updated successfully.');
    }
}
