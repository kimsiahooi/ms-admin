<?php

namespace App\Http\Controllers\Tenant;

use App\Enums\Tenant\Plant\Department\Operation\Status;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Department;
use App\Models\Tenant\Plant;
use App\Models\Tenant\Operation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OperationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Plant $plant, Department $department)
    {
        $entries = $request->input('entries', 10);

        $operations = $department->operations()->when(
            $request->search,
            fn(Builder $query, $search) =>
            $query->whereAny(['id', 'name', 'code'], 'like', "%{$search}%")
        )
            ->when(
                $request->status,
                fn(Builder $query, $status) =>
                $query->whereIn('status', $status)
            )->latest()
            ->paginate($entries)
            ->withQueryString();

        return inertia('Tenant/Plants/Departments/Operations/Index', [
            'plant' => $plant,
            'department' => $department,
            'operations' => $operations,
            'options' => [
                'select' => [
                    'statuses' => Status::selectOptions(),
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
            'name' => ['required', 'string', 'max:255'],
            'code' => [
                'required',
                'string',
                'alpha_dash',
                'max:255',
                Rule::unique('operations')
                    ->where('department_id', $department->id)
                    ->where('tenant_id', $plant->tenant_id)
                    ->where('tenant_id', $department->tenant_id)
                    ->where('tenant_id', tenant('id'))
            ],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'boolean'],
        ]);

        $validated['status'] = Status::toggleStatus($validated['status']);

        $department->operations()->create($validated);

        return back()->with('success', 'Operation created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Plant $plant, Department $department, Operation $operation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Plant $plant, Department $department, Operation $operation)
    {
        return inertia('Tenant/Plants/Departments/Operations/Edit', [
            'plant' => $plant,
            'department' => $department,
            'operation' => $operation,
            'options' => [
                'switch' => [
                    'statuses' => Status::switchOptions(),
                ],
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Plant $plant, Department $department, Operation $operation)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => [
                'required',
                'string',
                'alpha_dash',
                'max:255',
                Rule::unique('operations')
                    ->ignore($operation->id)
                    ->where('department_id', $department->id)
                    ->where('tenant_id', $plant->tenant_id)
                    ->where('tenant_id', $department->tenant_id)
                    ->where('tenant_id', $operation->tenant_id)
                    ->where('tenant_id', tenant('id'))
            ],
            'description' => ['nullable', 'string'],
            'status' => ['sometimes', 'boolean'],
        ]);

        if (isset($validated['status'])) {
            $validated['status'] = Status::toggleStatus($validated['status']);
        }

        $operation->update($validated);

        return to_route('plants.departments.operations.index', ['tenant' => tenant('id'), 'plant' => $plant->id, 'department' => $department->id])
            ->with('success', 'Operation updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Plant $plant, Department $department, Operation $operation)
    {
        $operation->delete();

        return back()->with('success', 'Operation deleted successfully.');
    }

    public function toggleStatus(Request $request, Plant $plant, Department $department, Operation $operation)
    {
        $validated = $request->validate([
            'status' => ['required', 'boolean'],
        ]);

        $validated['status'] = Status::toggleStatus($validated['status']);

        $operation->update($validated);

        return back()->with('success', 'Operation status updated successfully.');
    }
}
