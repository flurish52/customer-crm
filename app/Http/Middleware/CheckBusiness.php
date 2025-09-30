<?php

namespace App\Http\Middleware;

use Closure;

class CheckBusiness
{
    public function handle($request, Closure $next)
    {
        $user = $request->user();
        if ($user) {
            $business = \App\Models\Business::where('user_id', $user->id)->first();
            if (!$business && $request->path() !== 'business/setup') {
                return redirect('/business/setup');
            }
        }

        return $next($request);
    }
}
