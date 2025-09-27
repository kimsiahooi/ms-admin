<?php

namespace App\Http\Controllers\Tenant;

use App\Enums\Tenant\Plant\Department\Status;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Department;
use App\Models\Tenant\Plant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Plant $plant)
    {
        $entries = $request->input('entries', 10);

        $departments = $plant->departments()->with(['tasks'])->when(
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

        return inertia('Tenant/Plants/Departments/Index', [
            'departments' => $departments,
            'plant' => $plant,
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
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Plant $plant)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => [
                'required',
                'string',
                'alpha_dash',
                'max:255',
                Rule::unique('departments')
                    ->where('plant_id', $plant->id)
                    ->where('tenant_id', $plant->tenant_id)
                    ->where('tenant_id', tenant('id'))
            ],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'boolean'],
        ]);

        $validated['status'] = Status::toggleStatus($validated['status']);

        $plant->departments()->create($validated);

        return back()->with('success', 'Department created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Plant $plant, Department $department)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Plant $plant, Department $department)
    {
        return inertia('Tenant/Plants/Departments/Edit', [
            'plant' => $plant,
            'department' => $department,
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
    public function update(Request $request, Plant $plant, Department $department)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => [
                'required',
                'string',
                'alpha_dash',
                'max:255',
                Rule::unique('departments')
                    ->ignore($department->id)
                    ->where('plant_id', $plant->id)
                    ->where('tenant_id', $plant->tenant_id)
                    ->where('tenant_id', $department->tenant_id)
                    ->where('tenant_id', tenant('id'))
            ],
            'description' => ['nullable', 'string'],
            'status' => ['sometimes', 'boolean'],
        ]);

        if (isset($validated['status'])) {
            $validated['status'] = Status::toggleStatus($validated['status']);
        }

        $department->update($validated);

        return to_route('plants.departments.index', ['tenant' => tenant('id'), 'plant' => $plant->id])
            ->with('success', 'Department updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Plant $plant, Department $department)
    {
        $department->delete();

        return back()->with('success', 'Department deleted successfully.');
    }

    public function toggleStatus(Request $request, Plant $plant, Department $department)
    {
        $validated = $request->validate([
            'status' => ['required', 'boolean'],
        ]);

        $validated['status'] = Status::toggleStatus($validated['status']);

        $department->update($validated);

        return back()->with('success', 'Department status updated successfully.');
    }
}
