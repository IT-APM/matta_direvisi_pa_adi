<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class LogActivityMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $user = auth()->user();

        activity()
            ->causedBy($user)
            // ->performedOn($user)
            ->withProperties([
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'request' => $request->except(['password', 'password_confirmation']),
                'response_status' => $response->getStatusCode(),
            ])
            ->log($this->getActionName($request));

        return $response;
    }

    protected function getActionName($request)
    {
        if ($request->route()) {
            return $request->route()->getActionName();
        }

        return 'unknown_action';
    }
}
