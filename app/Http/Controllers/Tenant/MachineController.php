<?php

namespace App\Http\Controllers\Tenant;

use App\enums\Tenant\Machine\Status;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Machine;
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

        $machines = Machine::when($request->search, function ($query, $search) {
            $query->where('name', 'like', "%{$search}%");
        })->latest()
            ->paginate($entries)
            ->withQueryString();

        return inertia('Tenant/Machines/Index', [
            'machines' => $machines,
            'statuses' => collect(Status::cases())->map(function ($status) {
                return [
                    'name' => $status->dislay(),
                    'value' => $status->value,
                ];
            }),
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
            'code' => ['required', 'string', 'max:255', Rule::unique('machines', 'code')->where(function ($query) {
                return $query->where('tenant_id', tenant('id'));
            })],
            'description' => ['nullable', 'string'],
            'is_active' => ['required', 'boolean'],
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Machine $machine)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255', Rule::unique('machines', 'code')->ignore($machine->id)->where(function ($query) {
                return $query->where('tenant_id', tenant('id'));
            })],
            'description' => ['nullable', 'string'],
            'is_active' => ['required', 'boolean'],
        ]);

        $machine->update($validated);

        return back()->with('success', 'Machine updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Machine $machine)
    {
        $machine->delete();

        return back()->with('success', 'Machine deleted successfully.');
    }
}
