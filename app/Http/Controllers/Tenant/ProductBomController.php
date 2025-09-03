<?php

namespace App\Http\Controllers\Tenant;

use App\enums\Tenant\Material\UnitType;
use App\enums\Tenant\Product\Bom\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Product\Bom\StoreBomRequest;
use App\Http\Requests\Tenant\Product\Bom\UpdateBomRequest;
use App\Models\Tenant\Bom;
use App\Models\Tenant\Material;
use App\Models\Tenant\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ProductBomController extends Controller
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
                'statuses' => collect(Status::cases())
                    ->map(fn(Status $status) => [
                        'name' => $status->label(),
                        'value' => $status->value,
                        'is_default' => $status->value === Status::ACTIVE->value,
                    ]),
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, Product $product)
    {
        $materials = Material::all();

        return inertia('Tenant/Products/Boms/Create', [
            'product' => $product,
            'materials' => $materials,
            'options' => [
                'statuses' => collect(Status::cases())
                    ->map(fn(Status $status) => [
                        'name' => $status->label(),
                        'value' => $status->value,
                        'is_default' => $status->value === Status::ACTIVE->value,
                    ]),
                'unit_types' => collect(UnitType::cases())
                    ->map(fn(UnitType $type) => [
                        'name' => $type->label(),
                        'value' => $type->value,
                    ]),
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBomRequest $request, Product $product)
    {
        $validated = $request->validated();

        $bom = $product->boms()->create($validated);

        $materials = collect($validated['materials'])
            ->mapWithKeys(fn($m) => [
                $m['id'] => [
                    'unit_type' => $m['unit_type'],
                    'quantity'  => $m['quantity'],
                ],
            ])->toArray();

        $bom->materials()->sync($materials);

        return to_route('products.boms.index', [
            'tenant' => tenant('id'),
            'product' => $product->id
        ])->with('success', 'Product Bom created successfully.');
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
        $materials = Material::all();

        return inertia('Tenant/Products/Boms/Edit', [
            'product' => $product,
            'bom' => $bom->load(['materials']),
            'materials' => $materials,
            'options' => [
                'statuses' => collect(Status::cases())
                    ->map(fn(Status $status) => [
                        'name' => $status->label(),
                        'value' => $status->value,
                    ]),
                'unit_types' => collect(UnitType::cases())
                    ->map(fn(UnitType $type) => [
                        'name' => $type->label(),
                        'value' => $type->value,
                    ]),
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

        $bom->materials()->sync($materials);

        return to_route('products.boms.index', [
            'tenant' => tenant('id'),
            'product' => $product->id
        ])->with('success', 'Product Bom updated successfully.');
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
