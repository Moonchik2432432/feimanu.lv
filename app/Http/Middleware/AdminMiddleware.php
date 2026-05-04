<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    // Pārbauda, vai lietotājs ir autorizēts un vai viņam ir administratora tiesības
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403);
        }

        return $next($request);
    }
}