<?php

namespace App\Http\Controllers\Tenant;

use App\Enums\Tenant\Route\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Route\StoreRouteRequest;
use App\Http\Requests\Tenant\Route\UpdateRouteRequest;
use App\Models\Tenant\Plant;
use App\Models\Tenant\Route;
use App\Models\Tenant\RouteTask;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class RouteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $entries = $request->input('entries', 10);

        $routes = Route::when(
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

        return inertia('Tenant/Routes/Index', [
            'routes' => $routes,
            'options' => [
                'select' => [
                    'statuses' => Status::selectOptions(),
                ],
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        return inertia('Tenant/Routes/Create', [
            'plants' => Plant::active()
                ->withWhereHas('operations', fn($query) =>
                $query->active()->withWhereHas('tasks', fn($q) => $q->active()))
                ->get(),
            'options' => [
                'switch' => [
                    'statuses' => Status::switchOptions(),
                ],
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRouteRequest $request)
    {
        $validated = $request->validated();

        $validated['status'] = Status::toggleStatus($validated['status']);

        $route = Route::create($validated);

        $tasks = collect($validated['tasks'])->pluck('task_id');

        $route->tasks()->sync($tasks);

        return to_route('routes.index', ['tenant' => tenant('id')])
            ->with('success', 'Route created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Route $route)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Route $route)
    {
        $route->load(['tasks.operation.plant']);

        $plantIds     = $route->tasks->pluck('operation.plant_id')->filter()->unique();
        $operationIds = $route->tasks->pluck('operation_id')->filter()->unique();
        $taskIds      = $route->tasks->pluck('id');

        return inertia('Tenant/Routes/Edit', [
            'route' => $route,
            'plants' => Plant::where(fn($query) =>
            $query->active()->orWhere(fn($query) =>
            $query->whereIn('id', $plantIds)))
                ->withWhereHas('operations', fn($query) =>
                $query->where(fn($query) =>
                $query->active()->orWhere(fn($query) =>
                $query->whereIn('id', $operationIds)))
                    ->withWhereHas('tasks', fn($query) =>
                    $query->active()->orWhere(fn($query) =>
                    $query->whereIn('id', $taskIds))))
                ->get(),
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
    public function update(UpdateRouteRequest $request, Route $route)
    {
        $validated = $request->validated();

        if (isset($validated['status'])) {
            $validated['status'] = Status::toggleStatus($validated['status']);
        }

        $route->update($validated);

        $tasks = collect($validated['tasks'])->pluck('task_id');

        RouteTask::onlyTrashed()
            ->where('route_id', $route->id)
            ->whereIn('task_id', $tasks)
            ->restore();

        $route->tasks()->sync($tasks);

        return to_route('routes.index', ['tenant' => tenant('id')])
            ->with('success', 'Route updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Route $route)
    {
        $route->delete();

        return back()->with('success', 'Route deleted successfully.');
    }

    public function toggleStatus(Request $request, Route $route)
    {
        $validated = $request->validate([
            'status' => ['required', 'boolean'],
        ]);

        $validated['status'] = Status::toggleStatus($validated['status']);

        $route->update($validated);

        return back()->with('success', 'Route status updated successfully.');
    }
}
