<?php

namespace App\Http\Middleware;

use ArtisanCloud\SaaSFramework\Http\Controllers\API\APIResponse;
use App\Services\UserService;
use Closure;

class CheckUser
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

        $artisan = \Auth::user();
        dd($artisan);
        UserService::setAuthUser($user);

        if(!is_null($user) && !$user->isValid()){
            $this->setCode(API_ERR_CODE_INVALID_LOGIN_USER);
            return $this->getJSONResponse();
        }

        return $next($request);
    }
}
