<?php

namespace App\Http\Controllers\Tenant;

use App\enums\Tenant\Material\UnitType;
use App\enums\Tenant\Product\Bom\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Product\Bom\StoreBomRequest;
use App\Http\Requests\Tenant\Product\Bom\UpdateBomRequest;
use App\Models\Tenant\Bom;
use App\Models\Tenant\BomMaterial;
use App\Models\Tenant\Material;
use App\Models\Tenant\Product;
use Illuminate\Http\Request;

class ProductBomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Product $product)
    {
        $entries = $request->input('entries', 10);

        $boms = $product->boms()->when($request->search, function ($query, $search) {
            $query->where('name', 'like', "%{$search}%")->orWhere('id', 'like', "%{$search}%");
        })->latest()
            ->paginate($entries)
            ->withQueryString();

        return inertia('Tenant/Products/Boms/Index', [
            'product' => $product,
            'boms' => $boms,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, Product $product)
    {
        $materials = Material::active()->get();

        return inertia('Tenant/Products/Boms/Create', [
            'product' => $product,
            'materials' => $materials,
            'options' => [
                'statuses' => collect(Status::cases())->map(fn(Status $status) => ['name' => $status->display(), 'value' => $status->value]),
                'unit_types' => collect(UnitType::cases())->map(fn(UnitType $type) => ['name' => $type->display(), 'value' => $type->value]),
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBomRequest $request, Product $product)
    {
        $validated = $request->validated();

        $bom = Bom::onlyTrashed()->where('code', $validated['code'])->first();

        if ($bom) {
            $bom->restore();
            $bom->update($validated);
            $bom->refresh();
        } else {
            $bom = $product->boms()->create($validated);
        }

        $materials = collect($validated['materials'])
            ->mapWithKeys(fn($m) => [
                $m['id'] => [
                    'unit_type' => $m['unit_type'],
                    'quantity'  => $m['quantity'],
                ],
            ])->toArray();

        BomMaterial::withoutTrashed()
            ->where('bom_id', $bom->id)
            ->delete();

        BomMaterial::onlyTrashed()
            ->where('bom_id', $bom->id)
            ->whereIn('material_id', array_keys($materials))
            ->restore();

        $bom->materials()->sync($materials);

        return to_route('products.boms.index', ['tenant' => tenant('id'), 'product' => $product->id])->with('success', 'Product Bom created successfully.');
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
        $materials = Material::active()->get();

        return inertia('Tenant/Products/Boms/Edit', [
            'product' => $product,
            'bom' => $bom->load('materials'),
            'materials' => $materials,
            'options' => [
                'statuses' => collect(Status::cases())->map(fn(Status $status) => ['name' => $status->display(), 'value' => $status->value]),
                'unit_types' => collect(UnitType::cases())->map(fn(UnitType $type) => ['name' => $type->display(), 'value' => $type->value]),
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBomRequest $request, Product $product, Bom $bom)
    {
        $validated = $request->validated();

        $bom->update($validated);

        $materials = collect($validated['materials'])
            ->mapWithKeys(fn($m) => [
                $m['id'] => [
                    'unit_type' => $m['unit_type'],
                    'quantity'  => $m['quantity'],
                ],
            ])->toArray();

        BomMaterial::withoutTrashed()
            ->where('bom_id', $bom->id)
            ->whereNotIn('material_id', array_keys($materials))
            ->delete();

        BomMaterial::onlyTrashed()
            ->where('bom_id', $bom->id)
            ->whereIn('material_id', array_keys($materials))
            ->restore();

        $bom->materials()->sync($materials);

        return to_route('products.boms.index', ['tenant' => tenant('id'), 'product' => $product->id])->with('success', 'Product Bom updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product, Bom $bom)
    {
        $bom->delete();

        return back()->with('success', 'Bom deleted successfully.');
    }
}
