<?php

namespace App\Http\Controllers\Tenant;

use App\Enums\Tenant\Plant\Operation\Status;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Operation;
use App\Models\Tenant\Plant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class OperationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Plant $plant)
    {
        $entries = $request->input('entries', 10);

        $operations = Operation::when(
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

        return inertia('Tenant/Plants/Operations/Index', [
            'operations' => $operations,
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Plant $plant, Operation $operation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Plant $plant, Operation $operation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Plant $plant, Operation $operation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Plant $plant, Operation $operation)
    {
        //
    }

    public function toggleStatus(Request $request, Plant $plant, Operation $operation)
    {
        $validated = $request->validate([
            'status' => ['required', 'boolean'],
        ]);

        $validated['status'] = Status::toggleStatus($validated['status']);

        $operation->update($validated);

        return back()->with('success', 'Operation status updated successfully.');
    }
}
