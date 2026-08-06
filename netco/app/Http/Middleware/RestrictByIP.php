<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RestrictByIP
{
    /**
     * List of allowed IPs.
     *
     * @var array
     */
    protected $allowedIps = [
        '185.19.77.102', // Replace with your allowed IPs
        '100.119.88.79',
        '192.168.21.22',
        '192.168.21.1',
        '162.158.12.157',
        '162.158.12.156',
        '162.158.12.154',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!in_array($request->ip(), $this->allowedIps)) {
            return response($request->ip(), Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
