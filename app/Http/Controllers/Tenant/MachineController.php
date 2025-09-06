<?php

namespace App\Http\Controllers\Tenant;

use App\enums\Tenant\Machine\Status;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Machine;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MachineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $entries = $request->input('entries', 10);

        $machines = Machine::when(
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

        return inertia('Tenant/Machines/Index', [
            'machines' => $machines,
            'options' => [
                'statuses' => Status::options(),
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
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => [
                'required',
                'string',
                'alpha_dash',
                'max:255',
                Rule::unique('machines')
                    ->where('tenant_id', tenant('id'))
            ],
            'description' => ['nullable', 'string'],
            'status' => ['required', Rule::enum(Status::class)],
        ]);

        Machine::create($validated);

        return back()->with('success', 'Machine created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Machine $machine)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Machine $machine)
    {
        return inertia('Tenant/Machines/Edit', [
            'machine' => $machine,
            'options' => [
                'statuses' => Status::options(),
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Machine $machine)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => [
                'required',
                'string',
                'alpha_dash',
                'max:255',
                Rule::unique('machines')
                    ->ignore($machine->id)
                    ->where('tenant_id', tenant('id'))
            ],
            'description' => ['nullable', 'string'],
            'status' => ['required', Rule::enum(Status::class)],
        ]);

        $machine->update($validated);

        return to_route('machines.index', ['tenant' => tenant('id')])->with('success', 'Machine updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Machine $machine)
    {
        $machine->delete();

        return back()->with('success', 'Machine deleted successfully.');
    }

    public function toggleStatus(Request $request, Machine $machine)
    {
        $data = [
            'status' => $machine->status === Status::ACTIVE->value ? Status::INACTIVE->value : Status::ACTIVE->value,
        ];

        $machine->update($data);

        return back()->with('success', 'Machine status updated successfully.');
    }
}
