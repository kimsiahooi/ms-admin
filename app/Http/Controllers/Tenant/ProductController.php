<?php

namespace App\Http\Controllers\Tenant;

use App\enums\Tenant\Product\Status;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Product;
use Illuminate\Database\Eloquent\Builder;
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

        $products = Product::when($request->search, function (Builder $query, $search) {
            $query->where('name', 'like', "%{$search}%")->orWhere('id', 'like', "%{$search}%");
        })->latest()
            ->paginate($entries)
            ->withQueryString();

        return inertia('Tenant/Products/Index', [
            'products' => $products,
            'options' => [
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
                Rule::unique('products')
                    ->where('tenant_id', tenant('id'))
            ],
            'description' => ['nullable', 'string'],
            'status' => ['required', Rule::enum(Status::class)],
        ]);

        Product::create($validated);

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
        return inertia('Tenant/Products/Edit', [
            'product' => $product,
            'options' => [
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
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => [
                'required',
                'string',
                'alpha_dash',
                'max:255',
                Rule::unique('products')
                    ->ignore($product->id, 'id')
                    ->where('tenant_id', tenant('id'))
            ],
            'description' => ['nullable', 'string'],
            'status' => ['required', Rule::enum(Status::class)],
        ]);

        $product->update($validated);

        return to_route('products.index', ['tenant' => tenant('id')])->with('success', 'Product updated successfully.');
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
