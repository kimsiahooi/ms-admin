<?php

namespace App\Http\Middleware;

use App\Enums\Admin\Tenant\Status;
use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyTenantStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $tenant = Tenant::where('id', tenant('id'))->where('status', Status::ACTIVE->value)->first();

        if (!$tenant) {
            abort(500);
        }

        return $next($request);
    }
}
