<?php

namespace App\Http\Controllers\Tenant;

use App\enums\tenant\Product\Status;
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

        $products = Product::with('materials')->when($request->search, function ($query, $search) {
            $query->where('name', 'like', "%{$search}%");
        })->latest()
            ->paginate($entries)
            ->withQueryString();

        return inertia('Tenant/Products/Index', [
            'products' => $products,
            'statuses' => collect(Status::cases())->map(function ($status) {
                return [
                    'name' => $status->dislay(),
                    'value' => $status->value,
                ];
            }),
            'options' => [
                'materials' => Material::active()->get()->map(fn(Material $material) => ['name' => $material->name, 'value' => $material->id]),
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
            'code' => ['required', 'string', 'max:255', 'unique:products,code'],
            'description' => ['nullable', 'string'],
            'shelf_life_days' => ['nullable', 'integer', 'min:1'],
            'is_active' => ['required', 'boolean'],
            'materials' => ['required', 'array'],
            'materials.*' => Rule::exists('materials', 'id')->where('is_active', true),
        ]);

        $product = Product::create($validated);

        $product->materials()->sync($validated['materials']);

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
            'code' => ['required', 'string', 'max:255', Rule::unique('products', 'code')->ignore($product->id, 'id')],
            'description' => ['nullable', 'string'],
            'is_active' => ['required', 'boolean'],
            'shelf_life_days' => ['nullable', 'integer', 'min:1'],
            'materials' => ['required', 'array'],
            'materials.*' => Rule::exists('materials', 'id')->where('is_active', true),
        ]);

        $product->update($validated);

        $product->materials()->sync($validated['materials']);

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
