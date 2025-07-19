<?php

namespace App\Http\Controllers\Tenant;

use App\enums\tenant\Product\Currency;
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

        $products = Product::with(['materials', 'prizes'])->when($request->search, function ($query, $search) {
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
                'currencies' => collect(Currency::cases())->map(fn($currency) => ['name' => $currency->value, 'value' => $currency->value]),
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
            'prizes' => ['required', 'array'],
            'prizes.*.id' => ['nullable'],
            'prizes.*.currency' => ['required', Rule::in(array_column(Currency::cases(), 'value'))],
            'prizes.*.value' => ['required', 'numeric', 'min:0'],
            'is_active' => ['required', 'boolean'],
            'materials' => ['required', 'array'],
            'materials.*' => [Rule::exists('materials', 'id')->where('is_active', true)],
        ]);

        $product = Product::create($validated);

        foreach ($validated['prizes'] as $prize) {
            $product->prizes()->create([
                'currency' => $prize['currency'],
                'prize' => $prize['value'],
            ]);
        }

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
            'prizes' => ['required', 'array'],
            'prizes.*.id' => ['nullable', 'exists:product_prizes,id'],
            'prizes.*.currency' => ['required', Rule::in(array_column(Currency::cases(), 'value'))],
            'prizes.*.value' => ['required', 'numeric', 'min:0'],
            'materials' => ['required', 'array'],
            'materials.*' => [Rule::exists('materials', 'id')->where('is_active', true)],
        ]);

        $product->update($validated);

        foreach ($validated['prizes'] as $prize) {
            if ($prize['id']) {
                $existingPrize = $product->prizes()->where('id', $prize['id'])->first();
                if ($existingPrize) {
                    $existingPrize->update([
                        'currency' => $prize['currency'],
                        'prize' => $prize['value'],
                    ]);
                }
            } else {
                $product->prizes()->create([
                    'currency' => $prize['currency'],
                    'prize' => $prize['value'],
                ]);
            }
        }

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
