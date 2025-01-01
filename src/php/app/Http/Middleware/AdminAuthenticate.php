<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\View;

use App\Repositories\Traits\AdminTrait;

class AdminAuthenticate
{
    use AdminTrait;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$this->isAdminLoggedIn()) {
            $request->session()->put('admin_intended_url', $request->fullUrl());
            return redirect()->guest('/login');
        }

        View::share('logged_in_admin', $this->getLoggedInAdmin());

        return $next($request);
    }
}
