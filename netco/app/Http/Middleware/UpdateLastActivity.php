<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UpdateLastActivity
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            DB::table('user_sessions')
                ->where('user_id', Auth::id())
                ->update(['last_activity' => now()]);
        }

        return $next($request);
    }
}
