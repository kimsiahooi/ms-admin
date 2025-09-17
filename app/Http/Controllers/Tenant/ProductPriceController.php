<?php

namespace App\Http\Controllers\Tenant;

use App\Enums\Tenant\Product\Price\Currency;
use App\Enums\Tenant\Product\Price\Status;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Product;
use App\Models\Tenant\ProductPrice;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductPriceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Product $product, Request $request)
    {
        $entries = $request->input('entries', 10);

        $prices = $product->prices()->when(
            $request->search,
            fn(Builder $query, $search) =>
            $query->whereAny(['id', 'currency'], 'like', "%{$search}%")
        )
            ->when(
                $request->status,
                fn(Builder $query, $status) =>
                $query->whereIn('status', $status)
            )->latest()
            ->paginate($entries)
            ->withQueryString();

        return inertia('Tenant/Products/Prices/Index', [
            'product' => $product,
            'prices' => $prices,
            'options' => [
                'select' => [
                    'statuses' => Status::selectOptions(),
                    'currencies' => Currency::selectOptions(),
                ],
                'switch' => [
                    'statuses' => Status::switchOptions(),
                ]
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
            'currency' => [
                'required',
                Rule::enum(Currency::class),
                Rule::unique('product_prices')
                    ->where('product_id', $product->id)
                    ->where('tenant_id', $product->tenant_id)
                    ->where('tenant_id', tenant('id'))
            ],
            'amount' => ['required', 'min:0.01', 'decimal:0,2', 'numeric', 'max:999999.99'],
            'status' => ['required', 'boolean'],
        ]);

        $validated['status'] = Status::toggleStatus($validated['status']);

        $product->prices()->create($validated);

        return back()->with('success', 'Product price created successfully.');
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
    public function edit(Request $request, Product $product, ProductPrice $price)
    {
        return inertia('Tenant/Products/Prices/Edit', [
            'product' => $product,
            'price' => $price,
            'options' => [
                'select' => [
                    'statuses' => Status::selectOptions(),
                    'currencies' => Currency::selectOptions(),
                ],
                'switch' => [
                    'statuses' => Status::switchOptions(),
                ]
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product, ProductPrice $price)
    {
        $validated = $request->validate([
            'currency' => [
                'required',
                Rule::enum(Currency::class),
                Rule::unique('product_prices')
                    ->ignore($price->id, 'id')
                    ->where('product_id', $product->id)
                    ->where('tenant_id', $product->tenant_id)
                    ->where('tenant_id', tenant('id'))
            ],
            'amount' => ['required', 'min:0.01', 'decimal:0,2', 'numeric', 'max:999999.99'],
            'status' => ['sometimes', 'boolean'],
        ]);

        if (isset($validated['status'])) {
            $validated['status'] = Status::toggleStatus($validated['status']);
        }

        $price->update($validated);

        return back()->with('success', 'Prices updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product, ProductPrice $price)
    {
        $price->delete();

        return back()->with('success', 'Price deleted successfully.');
    }

    public function toggleStatus(Request $request, Product $product, ProductPrice $price)
    {
        $validated = $request->validate([
            'status' => ['required', 'boolean'],
        ]);

        $validated['status'] = Status::toggleStatus($validated['status']);

        $price->update($validated);

        return back()->with('success', 'Price status updated successfully.');
    }
}
