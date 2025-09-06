<?php

namespace App\Http\Controllers\Tenant;

use App\enums\Tenant\Material\Status;
use App\enums\Tenant\Material\UnitType;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Material;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $entries = $request->input('entries', 10);

        $materials = Material::when(
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

        return inertia('Tenant/Materials/Index', [
            'materials' => $materials,
            'options' => [
                'statuses' => Status::options(),
                'unit_types' => UnitType::options(),
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
                Rule::unique('materials')
                    ->where('tenant_id', tenant('id'))
            ],
            'description' => ['nullable', 'string'],
            'unit_type' => ['required', Rule::enum(UnitType::class)],
            'status' => ['required', Rule::enum(Status::class)],
        ]);

        Material::create($validated);

        return back()->with('success', 'Material created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Material $material)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Material $material)
    {
        return inertia('Tenant/Materials/Edit', [
            'material' => $material,
            'options' => [
                'statuses' => Status::options(),
                'unit_types' => UnitType::options(),
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Material $material)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => [
                'required',
                'string',
                'alpha_dash',
                'max:255',
                Rule::unique('materials', 'code')
                    ->ignore($material->id)
                    ->where('tenant_id', tenant('id'))
            ],
            'description' => ['nullable', 'string'],
            'unit_type' => ['required', Rule::enum(UnitType::class)],
            'status' => ['required', Rule::enum(Status::class)],
        ]);

        $material->update($validated);

        return to_route('materials.index', ['tenant' => tenant('id')])->with('success', 'Material updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Material $material)
    {
        $material->delete();

        return back()->with('success', 'Material deleted successfully.');
    }

    public function toggleStatus(Request $request, Material $material)
    {
        $data = [
            'status' => $material->status === Status::ACTIVE->value ? Status::INACTIVE->value : Status::ACTIVE->value,
        ];

        $material->update($data);

        return back()->with('success', 'Material status updated successfully.');
    }
}
