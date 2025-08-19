<?php

namespace App\Http\Controllers\Tenant;

use App\enums\Tenant\Product\Currency;
use App\enums\Tenant\Product\Price\Status;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Product;
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

        $prices = $product->prices()->when($request->search, function ($query, $search) {
            $query->where('currency', 'like', "%{$search}%");
        })->latest()
            ->paginate($entries)
            ->withQueryString();

        return inertia('Tenant/Products/Prices/Index', [
            'product' => $product,
            'prices' => $prices,
            'options' => [
                'statuses' => collect(Status::cases())->map(fn($status) => [
                    'name' => $status->display(),
                    'value' => $status->value,
                ]),
                'currencies' => collect(Currency::cases())->map(fn(Currency $currency) => ['name' => $currency->value, 'value' => $currency->value]),
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
                Rule::in(Currency::cases()),
                Rule::unique('product_prices', 'currency')
                    ->where(
                        fn($query) => $query
                            ->where('tenant_id', tenant('id'))
                            ->where('product_id', $product->id)
                    )
            ],
            'amount' => ['required', 'min:0', 'numeric'],
            'is_active' => ['required', 'boolean'],
        ]);

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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
