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
        $url =  $request->getHost();
        $host = parse_url($url, PHP_URL_HOST);
        $tenant = Tanent::where('subdomain', $host)->firstOrFail();
        app()->instance('currentTenant', $tenant);

        return $next($request);
    }
}
