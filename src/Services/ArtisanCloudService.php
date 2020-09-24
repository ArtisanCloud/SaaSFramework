<?php
declare(strict_types=1);

namespace ArtisanCloud\SaaSFramework\Services;


use App\Services\UserService\UserService;
use ArtisanCloud\SaaSFramework\Models\ArtisanCloudModel;
use ArtisanCloud\SaaSFramework\Services\LandlordService\src\LandlordService;
use ArtisanCloud\SaaSFramework\Traits\CacheTimeout;
use ArtisanCloud\SaaSPolymer\Services\ArtisanService\src\ArtisanService;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Cache;

class ArtisanCloudService
{
    protected $m_model;

    const PAGE_DEFAULT = 1;
    const PER_PAGE_DEFAULT = 10;
    const TAG_NAME = 'artisancloud';

    public function __construct()
    {

    }

    /**
     * Get session.
     *
     * @return array $arraySession
     */
    public static function getSessions() : array
    {
        $artisan = ArtisanService::getAuthArtisan();
        $landlord = LandlordService::getSessionLandlord();
        $user = UserService::getAuthUser();
        $arraySession['artisan_uuid'] = $artisan ? $artisan->uuid : null;
        $arraySession['landlord_uuid'] = $landlord ? $landlord->uuid : null;
        $arraySession['user_uuid'] = $user ? $user->uuid : null;

        return $arraySession;
    }

    /**
     * Get model ArtisanCloudModel by key.
     *
     * @param array $whereConditions
     *
     * @return ArtisanCloudModel $cachedModel
     */
    public static function GetBy($Class, array $whereConditions)
    {
        return $Class::where($whereConditions)->first();
    }

    /**
     * Get cached model ArtisanCloudModel by key.
     *
     * @param string $keyName
     * @param string $keyValue
     *
     * @return ArtisanCloudModel $cachedModel
     */
    public function getCachedModelForClientByKey(string $keyName = 'uuid', $keyValue)
    {
        $className = get_class($this->m_model);
//        dd($className);
        $cacheTag = $this->m_model->getCacheTag();
        $cacheKey = $this->m_model->getItemCacheKey($keyName);
//        dd($cacheTag, $cacheKey, CacheTimeout::CACHE_TIMEOUT);
        $cachedModel = Cache::tags($cacheTag)->remember($cacheKey, CacheTimeout::CACHE_TIMEOUT_MONTH, function () use ($className, $keyName, $keyValue) {

            \Log::info("request " . $className . " service here, {$keyName}:{$keyValue} .");

            $model = static::GetBy($className, [
                $keyName => $keyValue,
                'status' => $className::STATUS_NORMAL
            ]);
//            dd(123, $model);

            return $model;
        });

        return $cachedModel;
    }

    public function makeBy($arrayData)
    {
        $this->m_model = $this->m_model->firstOrNew($arrayData);
        return $this->m_model;
    }

    public function createBy($arrayData)
    {
        $this->m_model = $this->makeBy($arrayData);
        $bResult = $this->m_model->save();

        return $bResult ? $this->m_model : null;
    }

    /**
     * Get list Builder or Pagination  normal query.
     *
     * @param array $_arrayConditions
     * @param int $_page
     * @param int $_perPage
     *
     * @return mixed $listBuilder
     */
    public function getList(array $_arrayConditions = [],
                            ?int $_page = NULL,
                            ?int $_perPage = null)
    {
//        dd(request('page'), request('perPage'));

        $_page = $_page ?? (request('page') ?? self::PAGE_DEFAULT);
        $_perPage = $_perPage ?? (request('perPage') ?? self::PER_PAGE_DEFAULT);
//        dd($_page, $_perPage);

//        dd($this->m_model);
        $qb = $this->m_model->select('*');

//        dd($qb->toSql());
//        dump($_perPage, $_page);

        return $qb->limit($_perPage)->offset(($_page - 1) * $_perPage);

    }

    /**
     * Get list Builder from client request for normal query.
     * The function could be override by sub class for specific query.
     *
     * @param array $para
     *
     * @return mixed $listBuilder
     */
    public function getListForClient($para = [])
    {
        $page = $para['page'] ?? null;
        $perPage = $para['perPage'] ?? null;

        $listBuilder = $this->getList($para, $page, $perPage)->whereIsActive();
//        dd($listBuilder);
        return $listBuilder;
    }


    /**
     * Get cached list from client request for normal query.
     * The function could be override by sub class for specific query
     *
     * @param array $para
     *
     * @return collection $cachedList
     */
    public function getCachedListForClient($para)
    {
        $cacheTag = $this->getCacheTag();
        $cacheKey = $this->getListCacheKey($para['page'], $para['perPage']);
//        dd($cacheTag, $cacheKey);
        $cachedList = Cache::tags($cacheTag)->remember($cacheKey, SYSTEM_CACHE_TIMEOUT, function () use ($para) {

            $this->logLocal('info', "request " . class_basename($this) . " list here page:{$para['page']}, perPage:{$para['perPage']}");

            $list = $this->getListForClient($para)->get();

            return $list;
        });

        return $cachedList;

    }


    /**
     * Get Cached Model Builder from client request for normal query.
     *
     * @param string $uuid
     *
     * @return Product $cachedModel
     */
    public function getCachedModelForClientByUUID($uuid)
    {
        $cacheTag = $this->getCacheTag();
        $cacheKey = $this->getItemCacheKey($uuid);
//        dd($cacheTag, $cacheKey);
        $cachedModel = Cache::tags($cacheTag)->remember($cacheKey, SYSTEM_CACHE_TIMEOUT, function () use ($uuid) {

            $this->logLocal('info', "request " . class_basename($this) . " model here, uuid:{$uuid} .");

            $detail = $this->getModelForClientByUUID($uuid);

            return $detail;
        });

        return $cachedModel;
    }


    /**
     * Get Model Builder from client request for normal query.
     *
     * @param string $uuid
     *
     * @return ArtisanCloudModel $object
     */
    public function getModelForClientByUUID($uuid)
    {
        $detail = $this->m_model->where([
            'uuid' => $uuid,
            'status' => $this->m_model::STATUS_NORMAL,
        ])->first();

        return $detail;
    }


    /**
     * Get Model Builder from any request for normal query.
     *
     * @param string $uuid
     *
     * @return Model $detail
     */
    public function getModelByUUID($uuid)
    {
        $detail = $this->m_model->where([
            'uuid' => $uuid,
        ])->first();

        return $detail;
    }


}