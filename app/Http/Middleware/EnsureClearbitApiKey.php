<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureClearbitApiKey {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards) {
        if (!$request->input('api_key')) {
            return response()->json([
                        'error' => [
                            'message' => '`api_key` (Clearbit) param missing'
                        ]
                            ], 403);
        }

        return $next($request);
    }

}
