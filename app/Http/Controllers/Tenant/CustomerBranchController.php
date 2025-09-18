<?php

namespace App\Http\Controllers\Tenant;

use App\Enums\Tenant\Customer\Branch\Status;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Customer;
use App\Models\Tenant\CustomerBranch;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CustomerBranchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Customer $customer)
    {
        $entries = $request->input('entries', 10);

        $branches = $customer->branches()->when(
            $request->search,
            fn(Builder $query, $search) =>
            $query->whereAny(['id', 'name', 'code'], 'like', "%{$search}%")
        )
            ->when(
                $request->status,
                fn(Builder $query, $status) =>
                $query->whereIn('status', $status)
            )->latest()
            ->paginate($entries)
            ->withQueryString();

        return inertia('Tenant/Customers/Branches/Index', [
            'customer' => $customer,
            'branches' => $branches,
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
    public function store(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => [
                'required',
                'string',
                'alpha_dash',
                'max:255',
                Rule::unique('customer_branches')
                    ->where('customer_id', $customer->id)
                    ->where('tenant_id', $customer->tenant_id)
                    ->where('tenant_id', tenant('id'))
            ],
            'description' => ['nullable', 'string'],
            'address' => ['required', 'string'],
            'status' => ['required', 'boolean'],
        ]);

        $validated['status'] = Status::toggleStatus($validated['status']);

        $customer->branches()->create($validated);

        return back()->with('success', 'Branch created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(CustomerBranch $branch)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Customer $customer, CustomerBranch $branch)
    {
        return inertia('Tenant/Customers/Branches/Edit', [
            'customer' => $customer,
            'branch' => $branch,
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
    public function update(Request $request, Customer $customer,  CustomerBranch $branch)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => [
                'required',
                'string',
                'alpha_dash',
                'max:255',
                Rule::unique('customer_branches')
                    ->ignore($branch->id)
                    ->where('customer_id', $customer->id)
                    ->where('tenant_id', $customer->tenant_id)
                    ->where('tenant_id', tenant('id'))
            ],
            'description' => ['nullable', 'string'],
            'address' => ['required', 'string'],
            'status' => ['sometimes', 'boolean'],
        ]);

        if (isset($validated['status'])) {
            $validated['status'] = Status::toggleStatus($validated['status']);
        }

        $branch->update($validated);

        return back()->with('success', 'Branch updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer, CustomerBranch $branch)
    {
        $branch->delete();

        return back()->with('success', 'Branch deleted successfully.');
    }

    public function toggleStatus(Request $request, Customer $customer, CustomerBranch $branch)
    {
        $validated = $request->validate([
            'status' => ['required', 'boolean'],
        ]);

        $validated['status'] = Status::toggleStatus($validated['status']);

        $branch->update($validated);

        return back()->with('success', 'Branch status updated successfully.');
    }
}
