<?php

namespace App\Http\Middleware;

use App\Models\Store;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware that creates requestedStore parameter.  Left as an example of middleware.
 */
class LoadRequestedStore
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function handle(Request $request, Closure $next): Response
    {
        $request->attributes->set(
            "requestedStore",
            Store::where('domain', $request->getHost())->firstOrFail()
        );

        return $next($request);
    }
}
