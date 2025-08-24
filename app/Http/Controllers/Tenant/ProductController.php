<?php

namespace App\Http\Controllers\Tenant;

use App\enums\Tenant\Product\ShelfLifeType;
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
                'statuses' => collect(Status::cases())->map(fn($status) => [
                    'name' => $status->display(),
                    'value' => $status->value,
                ]),
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
            'shelf_life_duration' => ['nullable', 'required_with:shelf_life_type',  'numeric', 'min:0.01'],
            'shelf_life_type' => ['nullable', 'required_with:shelf_life_duration', Rule::in(ShelfLifeType::cases())],
            'is_active' => ['required', 'boolean'],
        ]);

        $product = Product::onlyTrashed()->where('code', $validated['code'])->first();

        if ($product) {
            $product->restore();
            $product->update($validated);
        } else {
            $product = Product::create($validated);
        }

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
                'statuses' => collect(Status::cases())->map(fn($status) => [
                    'name' => $status->display(),
                    'value' => $status->value,
                ]),
                'shelf_life_types' => collect(ShelfLifeType::cases())->map(fn(ShelfLifeType $shelfLifeType) => ['name' => $shelfLifeType->display(), 'value' => $shelfLifeType->value]),
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
            'code' => ['required', 'string', 'alpha_dash', 'max:255', Rule::unique('products', 'code')->ignore($product->id, 'id')->where(function ($query) {
                return $query->where('tenant_id', tenant('id'));
            })],
            'description' => ['nullable', 'string'],
            'is_active' => ['required', 'boolean'],
            'shelf_life_duration' => ['nullable', 'required_with:shelf_life_type', 'numeric', 'min:0.01'],
            'shelf_life_type' => ['nullable', 'required_with:shelf_life_duration', Rule::in(ShelfLifeType::cases())],
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
        $product->prices()->delete();

        return back()->with('success', 'Product deleted successfully.');
    }
}
