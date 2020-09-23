<?php

namespace ArtisanCloud\SaaSFramework\Http\Middleware;

use App\Http\Controllers\API\APIResponse;
use App\Services\UserService;
use Closure;

class CheckClientHavingUser
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $user = \Auth::guard('api')->user();
        UserService::setAuthUser($user);

        return $next($request);
    }
}
