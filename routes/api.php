<?php
declare(strict_types=1);


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


$_methodAll = config('artisancloud.framework.methodAll');
$_methodGet = config('artisancloud.framework.methodGet');
$_methodPost = config('artisancloud.framework.methodPost');
$_methodPut = config('artisancloud.framework.methodPut');
$_methodDelete = config('artisancloud.framework.methodDelete');
$_API_VERSION = config('artisancloud.framework.API_VERSION');
$_namespaceRouteAPI = 'ArtisanCloud\SaaSFramework\Http\Controllers\API';
$_namespaceAPI = $_namespaceRouteAPI;

/** Rou **/
Route::match($_methodAll, "{$_API_VERSION}/", "{$_namespaceAPI}\\RouterAPIController@index");
Route::group(
    [
        'namespace' => $_namespaceAPI,
//        'domain' => $_WHITE_LIST_DOMAIN,
        'middleware' => ['checkHeader', 'auth:api', 'checkUser']
    ], function () use ($_methodGet, $_methodPost, $_methodPut, $_methodDelete) {

});