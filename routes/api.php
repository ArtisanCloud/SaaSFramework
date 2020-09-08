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


$_methodAll = config('artisancloud.framework.router.methodAll');
$_methodGet = config('artisancloud.framework.router.methodGet');
$_methodPost = config('artisancloud.framework.router.methodPost');
$_methodPut = config('artisancloud.framework.router.methodPut');
$_methodDelete = config('artisancloud.framework.router.methodDelete');
$_api_version = config('artisancloud.framework.api_version');
$_namespaceRouteAPI = 'ArtisanCloud\SaaSFramework\Http\Controllers\API';
$_namespaceAPI = $_namespaceRouteAPI;

$_domain_landlord = config('artisancloud.framework.domain.landlord');
$_domain_tenant = config('artisancloud.framework.domain.tenant');

/** Router **/
Route::match($_methodAll, "api/{$_api_version}/", "{$_namespaceRouteAPI}\\RouterAPIController@index");




