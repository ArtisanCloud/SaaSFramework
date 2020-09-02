<?php
declare(strict_types=1);

namespace ArtisanCloud\SaaSFramework\Http\Controllers\API;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\ControllerMiddlewareOptions;

class RouterAPIController extends APIController
{
    //


    /**
     * Constructor.
     *
     * @param  \Illuminate\Http\Request $request
     * @return $mix
     */
    public function __construct(Request $request)
    {

        parent::__construct($request);

//        $this->configAuthMiddleware($request);

    }


    /**
     * API Gateway
     * name: {object}.{action}.{function}
     * description: gateway
     *
     * @bodyParam platform string required HeaderData
     * @bodyParam source string required HeaderData
     * @bodyParam uuid string required HeaderData depends on platform and source
     *
     *
     * @return JsonResponse
     */
    public function index(Request $request) : JsonResponse
    {

//		dd($request);
//		return \Route::redirect('machine1');
//		dd($request);
        $method = \Request::header('method');
//		dump($method);
        $uri = route($method, [], false);
//        dump($uri);

        $reqMethod = $request->method();

//        dump(\Auth::user());

        $server = $request->server();
        $server['SERVER_NAME'] = env('APP_WHITE_LIST_URL');
        $server['HTTP_HOST'] = env('APP_WHITE_LIST_URL');
        $server['REQUEST_URI'] = $uri;
        $req = $request->duplicate(null, null, null, null, null, $server);
        $req->setMethod($reqMethod);
//        dump($req);
        $response = \Route::dispatch($req);
//        dd($response);
        return $response;

    }


    /**
     * Config auth middleware.
     *
     * @param  Request $request
     * @return ControllerMiddlewareOptions $options
     */
    public function configAuthMiddleware(Request $request) : ControllerMiddlewareOptions
    {

        $provider = $this::getRequestProvider();
//		dd($provider);
        $guard = $this::getGuardFromRequestProvider($provider);
//		dd($guard);

        if ($provider == 'client') {
            $options = $this->middleware(["{$guard}", 'passportAccessToken']);
        } else {
            // ToCheck
            $options = $this->middleware(["auth:{$guard}", 'passportAccessToken']);
        }
//		dd($this);
        return $options;

    }


    /**
     * Get request provider.
     *
     *
     * @return string $provider
     */
    public static function getRequestProvider() : string
    {

        $provider = \Request::header('provider');
        if (is_null($provider) || $provider == '') {
            $provider = "users";
        }

        return $provider;
    }

    /**
     * Get guard from request provider.
     *
     * @param string $provider
     *
     * @return string $guard
     */
    public static function getGuardFromRequestProvider(string $provider) : string
    {

        // read config data
        $providerToGuard = \Config::get('auth.providerToGuard');
//		dd($providerToGuard);

        $guard = $providerToGuard[$provider];
//        dd($guard);

        return $guard;
    }

}
