<?php

namespace App\Http\Controllers\Tenant;

use App\enums\Tenant\Product\Preset\CavityType;
use App\enums\Tenant\Product\Preset\CycleTimeType;
use App\enums\Tenant\Product\Preset\ShelfLifeType;
use App\enums\Tenant\Product\Preset\Status;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Machine;
use App\Models\Tenant\Product;
use App\Models\Tenant\ProductPreset;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductPresetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Product $product)
    {
        $entries = $request->input('entries', 10);

        $presets = $product->presets()->with('machine')->when($request->search, function (Builder $query, $search) {
            $query->where('name', 'like', "%{$search}%")->orWhere('id', 'like', "%{$search}%");
        })->latest()
            ->paginate($entries)
            ->withQueryString();

        return inertia('Tenant/Products/Presets/Index', [
            'product' => $product,
            'presets' => $presets,
            'options' => [
                'machines' => Machine::active()
                    ->get()
                    ->map(fn(Machine $machine) => [
                        'name' => $machine->name,
                        'value' => $machine->id
                    ]),
                'cavity_types' => collect(CavityType::cases())
                    ->map(fn(CavityType $type) => [
                        'name' => $type->label(),
                        'value' => $type->value,
                    ]),
                'cycle_time_types' => collect(CycleTimeType::cases())
                    ->map(fn(CycleTimeType $type) => [
                        'name' => $type->label(),
                        'value' => $type->value,
                    ]),
                'statuses' => collect(Status::cases())
                    ->map(fn($status) => [
                        'name' => $status->label(),
                        'value' => $status->value,
                        'is_default' => $status->value === Status::ACTIVE->value,
                    ]),
                'shelf_life_types' => collect(ShelfLifeType::cases())
                    ->map(fn(ShelfLifeType $shelfLifeType) => [
                        'name' => $shelfLifeType->label(),
                        'value' => $shelfLifeType->value,
                    ]),
            ],
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
    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'machine_id' => [
                'required',
                Rule::exists('machines', 'id')
                    ->where('status', Status::ACTIVE->value)
                    ->where('tenant_id', $product->tenant_id)
                    ->where('tenant_id', tenant('id'))
            ],
            'name' => ['required', 'string', 'max:255'],
            'code' => [
                'required',
                'string',
                'max:255',
                'alpha_dash',
                Rule::unique('product_presets')
                    ->where('product_id', $product->id)
                    ->where('tenant_id', $product->tenant_id)
                    ->where('tenant_id', tenant('id'))
            ],
            'description' => ['nullable', 'string'],
            'cavity_quantity' => ['required', 'numeric', 'min:0'],
            'cavity_type' => ['required', Rule::enum(CavityType::class)],
            'cycle_time' => ['required', 'numeric', 'min:0.01'],
            'cycle_time_type' => ['required', Rule::enum(CycleTimeType::class)],
            'shelf_life_duration' => ['nullable', 'required_with:shelf_life_type',  'numeric', 'min:0.01'],
            'shelf_life_type' => [
                'nullable',
                'required_with:shelf_life_duration',
                Rule::enum(ShelfLifeType::class)
            ],
            'status' => ['required', Rule::enum(Status::class)],
        ]);

        $product->presets()->create($validated);

        return back()->with('success', 'Product preset created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product, ProductPreset $preset)
    {
        return inertia('Tenant/Products/Presets/Edit', [
            'product' => $product,
            'preset' => $preset->load(['machine']),
            'options' => [
                'machines' => Machine::active()
                    ->get()
                    ->map(fn(Machine $machine) => [
                        'name' => $machine->name,
                        'value' => $machine->id,
                    ]),
                'cavity_types' => collect(CavityType::cases())
                    ->map(fn(CavityType $type) => [
                        'name' => $type->label(),
                        'value' => $type->value,
                    ]),
                'cycle_time_types' => collect(CycleTimeType::cases())
                    ->map(fn(CycleTimeType $type) => [
                        'name' => $type->label(),
                        'value' => $type->value,
                    ]),
                'shelf_life_types' => collect(ShelfLifeType::cases())
                    ->map(fn(ShelfLifeType $shelfLifeType) => [
                        'name' => $shelfLifeType->label(),
                        'value' => $shelfLifeType->value
                    ]),
                'statuses' => collect(Status::cases())
                    ->map(fn($status) => [
                        'name' => $status->label(),
                        'value' => $status->value,
                        'is_default' => $status->value === Status::ACTIVE->value,
                    ]),
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product, ProductPreset $preset)
    {
        $validated = $request->validate([
            'machine_id' => [
                'required',
                Rule::exists('machines', 'id')
                    ->where('status', Status::ACTIVE->value)
                    ->where('tenant_id', $product->tenant_id)
                    ->where('tenant_id', tenant('id'))
            ],
            'name' => ['required', 'string', 'max:255'],
            'code' => [
                'required',
                'string',
                'max:255',
                'alpha_dash',
                Rule::unique('product_presets')
                    ->ignore($preset->id)
                    ->where('product_id', $product->id)
                    ->where('tenant_id', $product->tenant_id)
                    ->where('tenant_id', tenant('id'))
            ],
            'description' => ['nullable', 'string'],
            'cavity_quantity' => ['required', 'numeric', 'min:0'],
            'cavity_type' => ['required', Rule::enum(CavityType::class)],
            'cycle_time' => ['required', 'numeric', 'min:0'],
            'cycle_time_type' => ['required', Rule::enum(CycleTimeType::class)],
            'shelf_life_duration' => ['nullable', 'required_with:shelf_life_type', 'numeric', 'min:0.01'],
            'shelf_life_type' => ['nullable', 'required_with:shelf_life_duration', Rule::enum(ShelfLifeType::class)],
            'status' => ['required', Rule::enum(Status::class)],
        ]);

        $preset->update($validated);

        return to_route('products.presets.index', ['tenant' => tenant('id'), 'product' => $product->id])->with('success', 'Preset updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product, ProductPreset $preset)
    {
        $preset->delete();

        return back()->with('success', 'Preset deleted successfully.');
    }
}
