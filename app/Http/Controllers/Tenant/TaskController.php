<?php

namespace App\Http\Controllers\Tenant;

use App\Enums\Tenant\Plant\Department\Task\Status;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Department;
use App\Models\Tenant\Plant;
use App\Models\Tenant\Task;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Plant $plant, Department $department)
    {
        $entries = $request->input('entries', 10);

        $tasks = $department->tasks()->when(
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

        return inertia('Tenant/Plants/Departments/Tasks/Index', [
            'plant' => $plant,
            'department' => $department,
            'tasks' => $tasks,
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
                Rule::unique('tasks')
                    ->where('department_id', $department->id)
                    ->where('tenant_id', $plant->tenant_id)
                    ->where('tenant_id', $department->tenant_id)
                    ->where('tenant_id', tenant('id'))
            ],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'boolean'],
        ]);

        $validated['status'] = Status::toggleStatus($validated['status']);

        $department->tasks()->create($validated);

        return back()->with('success', 'Task created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Plant $plant, Department $department, Task $task)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Plant $plant, Department $department, Task $task)
    {
        return inertia('Tenant/Plants/Departments/Tasks/Edit', [
            'plant' => $plant,
            'department' => $department,
            'task' => $task,
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
    public function update(Request $request, Plant $plant, Department $department, Task $task)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => [
                'required',
                'string',
                'alpha_dash',
                'max:255',
                Rule::unique('tasks')
                    ->ignore($task->id)
                    ->where('department_id', $department->id)
                    ->where('tenant_id', $plant->tenant_id)
                    ->where('tenant_id', $department->tenant_id)
                    ->where('tenant_id', $task->tenant_id)
                    ->where('tenant_id', tenant('id'))
            ],
            'description' => ['nullable', 'string'],
            'status' => ['sometimes', 'boolean'],
        ]);

        if (isset($validated['status'])) {
            $validated['status'] = Status::toggleStatus($validated['status']);
        }

        $task->update($validated);

        return to_route('plants.departments.tasks.index', ['tenant' => tenant('id'), 'plant' => $plant->id, 'department' => $department->id])
            ->with('success', 'Task updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Plant $plant, Department $department, Task $task)
    {
        $task->delete();

        return back()->with('success', 'Task deleted successfully.');
    }

    public function toggleStatus(Request $request, Plant $plant, Department $department, Task $task)
    {
        $validated = $request->validate([
            'status' => ['required', 'boolean'],
        ]);

        $validated['status'] = Status::toggleStatus($validated['status']);

        $task->update($validated);

        return back()->with('success', 'Task status updated successfully.');
    }
}
