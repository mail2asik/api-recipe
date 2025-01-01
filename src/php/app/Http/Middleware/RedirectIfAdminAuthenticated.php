<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Repositories\Traits\AdminTrait;

class RedirectIfAdminAuthenticated
{
    use AdminTrait;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($this->isAdminLoggedIn()) {
            return redirect('dashboard');
        }

        return $next($request);
    }
}
