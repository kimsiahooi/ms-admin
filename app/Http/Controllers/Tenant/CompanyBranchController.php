<?php

namespace App\Http\Controllers\Tenant;

use App\Enums\Tenant\Company\Branch\Status;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Company;
use App\Models\Tenant\CompanyBranch;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CompanyBranchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Company $company)
    {
        $entries = $request->input('entries', 10);

        $branches = $company->branches()->when(
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

        return inertia('Tenant/Companies/Branches/Index', [
            'company' => $company,
            'branches' => $branches,
            'options' => [
                'statuses' => Status::options(),
                'switch_statuses' => Status::switchOptions(),
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
    public function store(Request $request, Company $company)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => [
                'required',
                'string',
                'alpha_dash',
                'max:255',
                Rule::unique('company_branches')
                    ->where('company_id', $company->id)
                    ->where('tenant_id', $company->tenant_id)
                    ->where('tenant_id', tenant('id'))
            ],
            'description' => ['nullable', 'string'],
            'address' => ['required', 'string'],
            'status' => ['required', 'boolean'],
        ]);

        $validated['status'] = Status::toggleStatus($validated['status']);

        $company->branches()->create($validated);

        return back()->with('success', 'Branch created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(CompanyBranch $branch)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Company $company, CompanyBranch $branch)
    {
        return inertia('Tenant/Companies/Branches/Edit', [
            'company' => $company,
            'branch' => $branch,
            'options' => [
                'statuses' => Status::options(),
                'switch_statuses' => Status::switchOptions(),
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Company $company,  CompanyBranch $branch)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => [
                'required',
                'string',
                'alpha_dash',
                'max:255',
                Rule::unique('company_branches')
                    ->ignore($branch->id)
                    ->where('company_id', $company->id)
                    ->where('tenant_id', $company->tenant_id)
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
    public function destroy(Company $company, CompanyBranch $branch)
    {
        $branch->delete();

        return back()->with('success', 'Branch deleted successfully.');
    }

    public function toggleStatus(Request $request, Company $company, CompanyBranch $branch)
    {
        $validated = $request->validate([
            'status' => ['required', 'boolean'],
        ]);

        $validated['status'] = Status::toggleStatus($validated['status']);

        $branch->update($validated);

        return back()->with('success', 'Branch status updated successfully.');
    }
}
