<?php

namespace App\Http\Controllers\Tenant;

use App\enums\Tenant\Product\Currency;
use App\enums\Tenant\Product\ShelfLifeType;
use App\enums\Tenant\Product\Status;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Material;
use App\Models\Tenant\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $entries = $request->input('entries', 10);

        $products = Product::with(['prices'])->when($request->search, function ($query, $search) {
            $query->where('name', 'like', "%{$search}%");
        })->latest()
            ->paginate($entries)
            ->withQueryString();

        return inertia('Tenant/Products/Index', [
            'products' => $products,
            'statuses' => collect(Status::cases())->map(function ($status) {
                return [
                    'name' => $status->display(),
                    'value' => $status->value,
                ];
            }),
            'options' => [
                'materials' => Material::active()->get()->map(fn(Material $material) => ['name' => "{$material->name} ({$material->code})", 'value' => $material->id]),
                'currencies' => collect(Currency::cases())->map(fn(Currency $currency) => ['name' => $currency->value, 'value' => $currency->value]),
                'shelf_life_types' => collect(ShelfLifeType::cases())->map(fn(ShelfLifeType $shelfLifeType) => ['name' => $shelfLifeType->display(), 'value' => $shelfLifeType->value]),
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
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'alpha_dash', 'max:255', Rule::unique('products', 'code')->where(function ($query) {
                return $query->where('tenant_id', tenant('id'))->whereNull('deleted_at');
            })],
            'description' => ['nullable', 'string'],
            'shelf_life_duration' => ['nullable', 'required_with:shelf_life_type', 'numeric', 'min:0.01'],
            'shelf_life_type' => ['nullable', Rule::in(ShelfLifeType::cases())],
            'prices' => ['required', 'array'],
            'prices.*.currency' => ['required', 'distinct', Rule::in(Currency::cases())],
            'prices.*.value' => ['required', 'numeric', 'min:0'],
            'is_active' => ['required', 'boolean'],
        ]);

        $validated['shelf_life_type'] = $validated['shelf_life_duration'] ? $validated['shelf_life_type'] : null;

        $product = Product::onlyTrashed()->where('code', $validated['code'])->first();

        if ($product) {
            $product->restore();
            $product->update($validated);
        } else {
            $product = Product::create($validated);
        }

        $priceIds = [];
        foreach ($validated['prices'] as $price) {
            $existingPrice = $product->prices()->onlyTrashed()->where('currency', $price['currency'])->first();

            if ($existingPrice) {
                $existingPrice->restore();
                $existingPrice->update([
                    'currency' => $price['currency'],
                    'price' => $price['value'],
                ]);

                $priceIds[] = $existingPrice->id;
            } else {
                $newPrice = $product->prices()->create([
                    'currency' => $price['currency'],
                    'price' => $price['value'],
                ]);

                $priceIds[] = $newPrice->id;
            }
        }

        $product->prices()->whereNotIn('id', $priceIds)->delete();

        return back()->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'alpha_dash', 'max:255', Rule::unique('products', 'code')->ignore($product->id, 'id')->where(function ($query) {
                return $query->where('tenant_id', tenant('id'));
            })],
            'description' => ['nullable', 'string'],
            'is_active' => ['required', 'boolean'],
            'shelf_life_duration' => ['nullable', 'numeric', 'min:0.01'],
            'shelf_life_type' => ['nullable', 'required_with:shelf_life_duration', Rule::in(ShelfLifeType::cases())],
            'prices' => ['required', 'array'],
            'prices.*.currency' => ['required', 'distinct', Rule::in(Currency::cases())],
            'prices.*.value' => ['required', 'numeric', 'min:0'],
        ]);

        $validated['shelf_life_type'] = $validated['shelf_life_duration'] ? $validated['shelf_life_type'] : null;

        $product->update($validated);

        $priceIds = [];
        foreach ($validated['prices'] as $price) {
            $existingPrice = $product->prices()->onlyTrashed()->where('currency', $price['currency'])->first();

            if ($existingPrice) {
                $existingPrice->restore();
                $existingPrice->update([
                    'currency' => $price['currency'],
                    'price' => $price['value'],
                ]);

                $priceIds[] = $existingPrice->id;
            } else {
                $existingPrice = $product->prices()->where('currency', $price['currency'])->first();

                if ($existingPrice) {
                    $existingPrice->update([
                        'currency' => $price['currency'],
                        'price' => $price['value'],
                    ]);

                    $priceIds[] = $existingPrice->id;
                } else {
                    $newPrice = $product->prices()->create([
                        'currency' => $price['currency'],
                        'price' => $price['value'],
                    ]);

                    $priceIds[] = $newPrice->id;
                }
            }
        }

        $product->prices()->whereNotIn('id', $priceIds)->delete();

        return back()->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return back()->with('success', 'Product deleted successfully.');
    }
}
