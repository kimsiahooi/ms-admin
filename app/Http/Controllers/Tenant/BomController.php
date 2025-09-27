<?php

namespace App\Http\Controllers\Tenant;

use App\Enums\Tenant\Material\UnitType;
use App\Enums\Tenant\Product\Bom\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Product\Bom\StoreBomRequest;
use App\Http\Requests\Tenant\Product\Bom\UpdateBomRequest;
use App\Models\Tenant\Bom;
use App\Models\Tenant\BomMaterial;
use App\Models\Tenant\Material;
use App\Models\Tenant\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class BomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Product $product)
    {
        $entries = $request->input('entries', 10);

        $boms = $product->boms()->when(
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

        return inertia('Tenant/Products/Boms/Index', [
            'product' => $product,
            'boms' => $boms,
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
    public function create(Request $request, Product $product)
    {
        $materials = Material::active()->get(['id', 'name', 'unit_type']);

        return inertia('Tenant/Products/Boms/Create', [
            'product' => $product,
            'materials' => $materials,
            'options' => [
                'select' => [
                    'unit_types' => UnitType::selectOptions(),
                ],
                'switch' => [
                    'statuses' => Status::switchOptions(),
                ]
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBomRequest $request, Product $product)
    {
        $validated = $request->validated();

        $validated['status'] = Status::toggleStatus($validated['status']);

        $bom = $product->boms()->create($validated);

        $materials = collect($validated['materials'])
            ->mapWithKeys(fn($m) => [
                $m['id'] => [
                    'unit_type' => $m['unit_type'],
                    'quantity'  => $m['quantity'],
                ],
            ])->toArray();

        $bom->materials()->sync($materials);

        return to_route('products.boms.index', ['tenant' => tenant('id'), 'product' => $product->id])
            ->with('success', 'Product Bom created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Bom $bom)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Product $product, Bom $bom)
    {
        $materialIds = $bom->materials()->pluck('material_id');

        $materials = Material::active()
            ->orWhere(fn($query) => $query->whereIn('id', $materialIds))
            ->get(['id', 'name', 'unit_type']);

        return inertia('Tenant/Products/Boms/Edit', [
            'product' => $product,
            'bom' => $bom->load(['materials']),
            'materials' => $materials,
            'options' => [
                'select' => [
                    'unit_types' => UnitType::selectOptions(),
                ],
                'switch' => [
                    'statuses' => Status::switchOptions(),
                ]
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBomRequest $request, Product $product, Bom $bom)
    {
        $validated = $request->validated();

        if (isset($validated['status'])) {
            $validated['status'] = Status::toggleStatus($validated['status']);
        }

        $bom->update($validated);

        $materials = collect($validated['materials'])
            ->mapWithKeys(fn($m) => [
                $m['id'] => [
                    'unit_type' => $m['unit_type'],
                    'quantity'  => $m['quantity'],
                ],
            ])->toArray();

        BomMaterial::onlyTrashed()
            ->where('bom_id', $bom->id)
            ->whereIn('material_id', array_keys($materials))
            ->restore();

        $bom->materials()->sync($materials);

        return to_route('products.boms.index', ['tenant' => tenant('id'), 'product' => $product->id])
            ->with('success', 'Product Bom updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product, Bom $bom)
    {
        $bom->delete();

        return back()->with('success', 'Product Bom deleted successfully.');
    }

    public function toggleStatus(Request $request, Product $product, Bom $bom)
    {
        $validated = $request->validate([
            'status' => ['required', 'boolean'],
        ]);

        $validated['status'] = Status::toggleStatus($validated['status']);

        $bom->update($validated);

        return back()->with('success', 'Product Bom status updated successfully.');
    }
}
