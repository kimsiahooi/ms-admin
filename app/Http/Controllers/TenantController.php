<?php

namespace App\Http\Controllers;

use App\Enums\Permissions\RolePermissionsEnum;
use App\Enums\Permissions\TenantPermissionsEnum;
use App\Enums\Permissions\UserPermissionsEnum;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class TenantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize(TenantPermissionsEnum::ViewTenant);

        $tenants = Tenant::latest()->get();

        return inertia('Central/Tenants/Index', [
            'tenants' => $tenants,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize(TenantPermissionsEnum::CreateTenant);

        return inertia('Central/Tenants/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize(TenantPermissionsEnum::CreateTenant);

        $request->validate([
            'id' => ['required', 'string', 'max:255', Rule::unique('tenants', 'id')],
            'name' => ['required', 'string', 'max:255'],
        ]);

        $tenant = Tenant::create([
            'id' => $request->id,
            'name' => $request->name,
        ]);

        $tenant->domains()->create([
            'domain' => $request->id,
        ]);

        return back()->with('success', 'Tenant created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        Gate::authorize(TenantPermissionsEnum::ViewTenant);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        Gate::authorize(TenantPermissionsEnum::EditTenant);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        Gate::authorize(TenantPermissionsEnum::EditTenant);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tenant $tenant)
    {
        Gate::authorize(TenantPermissionsEnum::DeleteTenant);

        $tenant->delete();

        return back()->with('success', 'Tenant deleted successfully.');
    }
}
