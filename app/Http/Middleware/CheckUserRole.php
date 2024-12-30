<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next,  ...$roles): Response
    {
         // Check if the user is authenticated
         if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Get the authenticated user
        $user = Auth::user();
        $roles = is_array($roles) ? $roles : [$roles];

        // Check if the user has the required role
        if (!$user->hasAnyRole($roles)) {
            // Optionally, you can redirect to a '403 Forbidden' page or a different route
            abort(403, 'Unauthorized action.');
        }
        return $next($request);
    }
}
