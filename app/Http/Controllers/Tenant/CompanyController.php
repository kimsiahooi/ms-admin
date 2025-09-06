<?php

namespace App\Http\Controllers\Tenant;

use App\Enums\Tenant\Company\Status;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Company;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $entries = $request->input('entries', 10);

        $companies = Company::when(
            $request->search,
            fn(Builder $query, $search) =>
            $query->whereAny(['id', 'name', 'code'], 'like', "%{$search}%")
        )
            ->when(
                $request->status,
                fn(Builder $query, $status) =>
                $query->whereIn('status', $status)
            )
            ->latest()
            ->paginate($entries)
            ->withQueryString();

        return inertia('Tenant/Companies/Index', [
            'companies' => $companies,
            'options' => [
                'statuses' => Status::options(),
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
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => [
                'required',
                'string',
                'alpha_dash',
                'max:255',
                Rule::unique('companies')
                    ->where('tenant_id', tenant('id'))
            ],
            'description' => ['nullable', 'string'],
            'status' => ['required', Rule::enum(Status::class)],
        ]);

        Company::create($validated);

        return back()->with('success', 'Company created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $company)
    {
        return inertia('Tenant/Companies/Edit', [
            'company' => $company,
            'options' => [
                'statuses' => Status::options(),
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Company $company)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => [
                'required',
                'string',
                'alpha_dash',
                'max:255',
                Rule::unique('companies')
                    ->ignore($company->id)
                    ->where('tenant_id', tenant('id'))
            ],
            'description' => ['nullable', 'string'],
            'status' => ['required', Rule::enum(Status::class)],
        ]);

        $company->update($validated);

        return to_route('companies.index', ['tenant' => tenant('id')])->with('success', 'Company updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        $company->delete();

        return back()->with('success', 'Company deleted successfully.');
    }

    public function toggleStatus(Request $request, Company $company)
    {
        $data = [
            'status' => $request->status ? Status::ACTIVE->value : Status::INACTIVE->value,
        ];

        $company->update($data);

        return back()->with('success', 'Company status updated successfully.');
    }
}
