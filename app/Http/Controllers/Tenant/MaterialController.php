<?php

namespace App\Http\Controllers\Tenant;

use App\enums\Tenant\Material\Status;
use App\enums\Tenant\Material\UnitType;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Material;
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

        $materials = Material::when($request->search, function ($query, $search) {
            $query->where('name', 'like', "%{$search}%");
        })->latest()
            ->paginate($entries)
            ->withQueryString();

        return inertia('Tenant/Materials/Index', [
            'materials' => $materials,
            'options' => [
                'statuses' => collect(Status::cases())->map(fn(Status $status) => ['name' => $status->display(), 'value' => $status->value]),
                'unit_types' => collect(UnitType::cases())->map(fn(UnitType $unitType) => ['name' => $unitType->display(), 'value' => $unitType->value]),
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
            'code' => ['required', 'string', 'alpha_dash', 'max:255', Rule::unique('materials', 'code')->where(function ($query) {
                return $query->where('tenant_id', tenant('id'))->whereNull('deleted_at');
            })],
            'description' => ['nullable', 'string'],
            'unit_type' => ['required', Rule::in(UnitType::cases())],
            'is_active' => ['required', 'boolean'],
        ]);

        $material = Material::onlyTrashed()->where('code', $validated['code'])->first();

        if ($material) {
            $material->restore();
            $material->update($validated);
        } else {
            Material::create($validated);
        }

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
                'statuses' => collect(Status::cases())->map(fn(Status $status) => ['name' => $status->display(), 'value' => $status->value]),
                'unit_types' => collect(UnitType::cases())->map(fn(UnitType $unitType) => ['name' => $unitType->display(), 'value' => $unitType->value]),
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
            'code' => ['required', 'string', 'alpha_dash', 'max:255', Rule::unique('materials', 'code')->ignore($material->id)->where(function ($query) {
                return $query->where('tenant_id', tenant('id'));
            })],
            'description' => ['nullable', 'string'],
            'unit_type' => ['required', Rule::in(UnitType::cases())],
            'is_active' => ['required', 'boolean'],
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
}
