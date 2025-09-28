<?php

namespace App\Http\Controllers\Tenant;

use App\Enums\Tenant\Plant\Department\TenantUser\Status;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Department;
use App\Models\Tenant\DepartmentTenantUser;
use App\Models\Tenant\Plant;
use App\Models\Tenant\TenantUser;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DepartmentTenantUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Plant $plant, Department $department)
    {
        $entries = $request->input('entries', 10);

        $users = $department->users()
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
                    'departments',
                    fn() => $query->whereIn('department_tenant_user.status', $status)
                )
            )
            ->orderByPivot('created_at', 'desc')
            ->paginate($entries)
            ->withQueryString();

        return inertia('Tenant/Plants/Departments/TenantUsers/Index', [
            'plant' => $plant,
            'department' => $department,
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
    public function create(Request $request, Plant $plant, Department $department)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Plant $plant, Department $department)
    {
        $validated = $request->validate([
            'user' => [
                'required',
                Rule::exists('tenant_users', 'id')
                    ->withoutTrashed()
                    ->where('tenant_id', $plant->id)
                    ->where('tenant_id', $department->id)
                    ->where('tenant_id', tenant('id')),
                Rule::unique('department_tenant_user', 'tuser_id')
                    ->where('department_id', $department->id)
                    ->where('tenant_id', $plant->id)
                    ->where('tenant_id', $department->id)
                    ->where('tenant_id', tenant('id'))
            ],
            'status' => ['required', 'boolean'],
        ]);

        $validated['status'] = Status::toggleStatus($validated['status']);

        $department->users()->attach([
            $validated['user'] => [
                'status' => $validated['status']
            ]
        ]);

        return back()->with('success', 'User attached successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Plant $plant, Department $department, DepartmentTenantUser $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Plant $plant, Department $department, DepartmentTenantUser $user)
    {
        return inertia('Tenant/Plants/Departments/TenantUsers/Edit', [
            'plant' => $plant,
            'department' => $department,
            'user' => $user->load(['user']),
            'options' => [
                'select' => [
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, Plant $plant, Department $department, DepartmentTenantUser $user)
    {
        $validated = $request->validate([
            'user' => [
                'required',
                Rule::exists('tenant_users', 'id')
                    ->withoutTrashed()
                    ->where('tenant_id', $plant->id)
                    ->where('tenant_id', $department->id)
                    ->where('tenant_id', $user->id)
                    ->where('tenant_id', tenant('id')),
                Rule::unique('department_tenant_user', 'tuser_id')
                    ->ignore($user->id)
                    ->where('department_id', $department->id)
                    ->where('tenant_id', $plant->id)
                    ->where('tenant_id', $department->id)
                    ->where('tenant_id', $user->id)
                    ->where('tenant_id', tenant('id'))
            ],
            'status' => ['sometimes', 'boolean'],
        ]);

        if (isset($validated['status'])) {
            $validated['status'] = Status::toggleStatus($validated['status']);
        }

        $user->update([
            'tuser_id' => $validated['user'],
            'status' => $validated['status'],
        ]);

        return to_route('plants.departments.users.index', ['tenant' => tenant('id'), 'plant' => $plant->id, 'department' => $department->id])
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Plant $plant, Department $department, DepartmentTenantUser $user)
    {
        $user->delete();

        return back()->with('success', 'User detached successfully.');
    }

    public function toggleStatus(Request $request, Plant $plant, Department $department, DepartmentTenantUser $user)
    {
        $validated = $request->validate([
            'status' => ['required', 'boolean'],
        ]);

        $validated['status'] = Status::toggleStatus($validated['status']);

        $user->update([
            'status' => $validated['status']
        ]);

        return back()->with('success', 'User status updated successfully.');
    }
}
