<?php

namespace App\Http\Controllers\Tenant;

use App\enums\Tenant\Product\Bom\Status;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Bom;
use App\Models\Tenant\Product;
use Illuminate\Http\Request;

class BomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Product $product, Request $request)
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
            'options' => [
                'products' => Product::active()->get()->map(fn(Product $product) => ['name' => $product->name, 'value' => $product->id]),
                'statuses' => collect(Status::cases())->map(fn(Status $status) => ['name' => $status->display(), 'value' => $status->value]),
            ]
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
        //
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
    public function edit(Bom $bom)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Bom $bom)
    {
        //
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
