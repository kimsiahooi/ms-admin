<?php

namespace App\Http\Controllers;

use App\Enums\Admin\Tenant\Status;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TenantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $entries = $request->input('entries', 10);

        $tenants = Tenant::when(
            $request->search,
            fn(Builder $query, $search) =>
            $query->whereAny(['id', 'name'], 'like', "%{$search}%")
        )
            ->when(
                $request->status,
                fn(Builder $query, $status) =>
                $query->whereIn('status', $status)
            )->latest()
            ->paginate($entries)
            ->withQueryString();

        return inertia('Admin/Tenants/Index', [
            'tenants' => $tenants,
            'options' => [
                'statuses' => collect(Status::cases())
                    ->map(function (Status $status) {
                        return [
                            'name' => $status->label(),
                            'value' => $status->value,
                            'is_default' => $status->value === Status::ACTIVE->value,
                        ];
                    }),
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia('Admin/Tenants/Create', [
            'options' => [
                'statuses' => collect(Status::cases())
                    ->map(function ($status) {
                        return [
                            'name' => $status->label(),
                            'value' => $status->value,
                            'is_default' => $status->value === Status::ACTIVE->value,
                        ];
                    }),
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Tenant::create($request->validate([
            'name' => ['required', 'string', 'max:255'],
            'id' => ['required', 'string', 'max:255', 'alpha_dash', 'unique:tenants,id'],
            'status' => ['required', Rule::enum(Status::class)],
        ]));

        return to_route('admin.tenants.index')->with('success', 'Tenant created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tenant $tenant)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tenant $tenant)
    {
        return inertia('Admin/Tenants/Edit', [
            'tenant' => $tenant,
            'options' => [
                'statuses' => collect(Status::cases())
                    ->map(function ($status) {
                        return [
                            'name' => $status->label(),
                            'value' => $status->value,
                        ];
                    }),
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tenant $tenant)
    {
        $tenant->update(($request->validate([
            'name' => ['required', 'string', 'max:255'],
            'status' => ['required', Rule::enum(Status::class)],
        ])));

        return to_route('admin.tenants.index')->with('success', 'Tenant created successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tenant $tenant)
    {
        $tenant->delete();

        return back()->with('success', 'Tenant deleted successfully.');
    }
}
