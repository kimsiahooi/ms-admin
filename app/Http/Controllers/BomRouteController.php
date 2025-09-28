<?php

namespace App\Http\Controllers;

use App\Enums\Tenant\Product\Bom\Status as BomStatus;
use App\Enums\Tenant\Product\Status as ProductStatus;
use App\Enums\Tenant\Route\Bom\Status;
use App\Models\Tenant\BomRoute;
use App\Models\Tenant\Product;
use App\Models\Tenant\Route;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BomRouteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Route $route)
    {
        $entries = $request->input('entries', 10);

        $boms = $route->boms()
            ->when(
                $request->search,
                fn(Builder $query, $search) =>
                $query->whereAny(
                    ['boms.id', 'boms.name', 'boms.code'],
                    'like',
                    "%{$search}%"
                )
            )
            ->when(
                $request->status,
                fn(Builder $query, $status) =>
                $query->whereHas(
                    'routes',
                    fn() => $query->whereIn('bom_route.status', $status)
                )
            )
            ->orderByPivot('created_at', 'desc')
            ->paginate($entries)
            ->withQueryString();

        return inertia('Tenant/Routes/Boms/Index', [
            'route' => $route,
            'boms' => $boms,
            'products' => Product::active()
                ->withWhereHas('boms', fn($query) => $query->active())
                ->get(),
            'options' => [
                'select' => [
                    'statuses' => Status::selectOptions(),
                ],
                'switch' => [
                    'statuses' => Status::switchOptions(),
                ],
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
    public function store(Request $request, Route $route)
    {
        $validated = $request->validate([
            'product_id' => [
                'required',
                Rule::exists('products', 'id')
                    ->withoutTrashed()
                    ->where(
                        fn(QueryBuilder $query) =>
                        $query->where('status', ProductStatus::ACTIVE->value)
                            ->where('tenant_id', $route->tenant_id)
                            ->where('tenant_id', tenant('id'))
                    )
            ],
            'bom_id' => [
                'required',
                Rule::exists('boms', 'id')
                    ->withoutTrashed()
                    ->where(
                        fn(QueryBuilder $query) =>
                        $query->where('status', BomStatus::ACTIVE->value)
                            ->where('product_id', $request->product_id)
                            ->where('tenant_id', $route->tenant_id)
                            ->where('tenant_id', tenant('id'))
                    ),
                Rule::unique('bom_route')
                    ->where(
                        fn(QueryBuilder $query) =>
                        $query->where('route_id', $route->id)
                            ->where('tenant_id', $route->tenant_id)
                            ->where('tenant_id', tenant('id'))
                    )
            ],
            'status' => ['required', 'boolean'],
        ], [], [
            'product_id' => 'product',
            'bom_id' => 'bom',
        ]);

        $validated['status'] = Status::toggleStatus($validated['status']);

        $route->boms()->attach([
            $validated['bom_id'] => [
                'status' => $validated['status']
            ]
        ]);

        return back()->with('success', 'Bom attached successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Route $route, BomRoute $bom)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Route $route, BomRoute $bom)
    {;

        return inertia('Tenant/Routes/Boms/Edit', [
            'route' => $route,
            'bom' => $bom->load(['bom.product']),
            'products' => Product::where(
                fn(Builder $query) =>
                $query->active()->when(
                    $bom->bom?->product_id,
                    fn(Builder $query) =>
                    $query->orWhere('id', $bom->bom->product_id)
                )
            )
                ->withWhereHas(
                    'boms',
                    fn($query) =>
                    $query->active()
                        ->orWhere('id', $bom->bom_id)
                )
                ->get(),
            'options' => [
                'switch' => [
                    'statuses' => Status::switchOptions(),
                ],
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Route $route, BomRoute $bom)
    {
        $validated = $request->validate([
            'product_id' => [
                'required',
                Rule::exists('products', 'id')
                    ->withoutTrashed()
                    ->where(fn(QueryBuilder $query) =>
                    $query->where('status', ProductStatus::ACTIVE->value)
                        ->where('tenant_id', $route->tenant_id)
                        ->where('tenant_id', $bom->tenant_id)
                        ->where('tenant_id', tenant('id')))
            ],
            'bom_id' => [
                'required',
                Rule::exists('boms', 'id')
                    ->withoutTrashed()
                    ->where(fn(QueryBuilder $query) =>
                    $query->where('status', BomStatus::ACTIVE->value)
                        ->where('product_id', $request->product_id)
                        ->where('tenant_id', $route->tenant_id)
                        ->where('tenant_id', $bom->tenant_id)
                        ->where('tenant_id', tenant('id'))),
                Rule::unique('bom_route')
                    ->ignore($bom->id)
                    ->where(
                        fn(QueryBuilder $query) =>
                        $query->where('route_id', $route->id)
                            ->where('tenant_id', $route->tenant_id)
                            ->where('tenant_id', tenant('id'))
                    )
            ],
            'status' => ['required', 'boolean'],
        ], [], [
            'product_id' => 'product',
            'bom_id' => 'bom',
        ]);

        $validated['status'] = Status::toggleStatus($validated['status']);

        $bom->update($validated);

        return to_route('routes.boms.index', ['tenant' => tenant('id'), 'route' => $route->id])
            ->with('success', 'Bom updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Route $route, BomRoute $bom)
    {
        $bom->delete();

        return back()->with('success', 'Bom detached successfully.');
    }

    public function toggleStatus(Request $request, Route $route, BomRoute $bom)
    {
        $validated = $request->validate([
            'status' => ['required', 'boolean'],
        ]);

        $validated['status'] = Status::toggleStatus($validated['status']);

        $bom->update($validated);

        return back()->with('success', 'Bom status updated successfully.');
    }
}
