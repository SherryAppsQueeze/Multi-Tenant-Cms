<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Tanents\Tanent;

class TenantMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Extract subdomain from request and find tenant
        $subdomain = explode('.', $request->getHost())[0];
        $tenant = Tanent::where('subdomain', $subdomain)->firstOrFail();
        app()->instance('currentTenant', $tenant);

        return $next($request);
    }
}
