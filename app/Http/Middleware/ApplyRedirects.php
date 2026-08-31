<?php

namespace App\Http\Middleware;

use App\Models\Redirect;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

/**
 * Honours the redirect table managed in the admin panel. Only runs when the
 * response is a 404, so a live route is never intercepted.
 */
class ApplyRedirects
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($response->getStatusCode() !== 404) {
            return $response;
        }

        $path = '/' . ltrim($request->path(), '/');

        $map = Cache::remember('redirects.map', 300, fn () =>
            Redirect::where('is_active', true)->pluck('id', 'from')->all());

        foreach ([$path, rtrim($path, '/')] as $candidate) {
            if (! isset($map[$candidate])) {
                continue;
            }

            $rule = Redirect::find($map[$candidate]);
            if (! $rule) {
                continue;
            }

            $rule->increment('hits');

            return redirect($rule->to, $rule->status);
        }

        return $response;
    }
}
