<?php
declare(strict_types=1);

namespace ArtisanCloud\SaaSFramework\Services;


use App\Services\UserService\UserService;
use ArtisanCloud\SaaSFramework\Models\ArtisanCloudModel;
use ArtisanCloud\SaaSMonomer\Services\LandlordService\src\LandlordService;
use ArtisanCloud\SaaSFramework\Traits\CacheTimeout;
use ArtisanCloud\SaaSPolymer\Services\ArtisanService\src\ArtisanService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
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
     * Set model.
     *
     * @return void
     */
    public function setModel($model)
    {
        $this->m_model = $model;
    }

    /**
     * Get model.
     *
     * @return mixed
     */
    public function getModel()
    {
        return $this->m_model;
    }

    /**
     * Get session.
     *
     * @return array
     */
    public static function getSessions(): array
    {
        $artisan = ArtisanService::getAuthArtisan();
        $user = UserService::getAuthUser();
        $landlord = LandlordService::getSessionLandlord();
        $arraySession['artisan'] = $artisan;
        $arraySession['user'] = $user;
        $arraySession['landlord'] = $landlord;

        return $arraySession;
    }

    /**
     * Get session UUIDs.
     *
     * @return array
     */
    public static function getSessionUUIDs(): array
    {
        $arraySession = static::getSessions();
        $arraySessionUUIDs['artisan_uuid'] = $arraySession['artisan'] ? $arraySession['artisan']->uuid : null;
        $arraySessionUUIDs['landlord_uuid'] = $arraySession['landlord'] ? $arraySession['landlord']->uuid : null;
        $arraySessionUUIDs['user_uuid'] = $arraySession['user'] ? $arraySession['user']->uuid : null;

        return $arraySessionUUIDs;
    }


    /**
     * make a model
     *
     * @param array $arrayData
     *
     * @return mixed
     */
    public function makeBy(array $arrayData)
    {
        $this->m_model = $this->m_model->firstOrNew($arrayData);
        return $this->m_model;
    }

    /**
     * create a model
     *
     * @param array $arrayData
     *
     * @return mixed
     */
    public function createBy(array $arrayData)
    {
        $this->m_model = $this->makeBy($arrayData);
        $bResult = $this->m_model->save();

        return $bResult ? $this->m_model : null;
    }


    /**
     * Get model ArtisanCloudModel by key.
     *
     * @param array $whereConditions
     *
     * @return mixed
     */
    public static function GetBy(array $whereConditions)
    {
//        dd($whereConditions);
        return static::GetItemsBy($whereConditions)->first();
    }

    /**
     * Get models ArtisanCloudModel by key.
     *
     * @param array $whereConditions
     *
     * @return Builder
     */
    public static function GetItemsBy(array $whereConditions)
    {
        $instance = new static();
        return $instance->m_model->where($whereConditions);
    }

    /**
     * Get model ArtisanCloudModel for client by key.
     *
     * @param array $whereConditions
     *
     * @return mixed
     */
    public static function GetItemForClientBy($Class, array $whereConditions)
    {
        return static::GetItemsForClientBy($Class, $whereConditions)->first();
    }

    /**
     * Get models ArtisanCloudModel for client by key.
     *
     * @param array $whereConditions
     *
     * @return Builder
     */
    public static function GetItemsForClientBy($Class, array $whereConditions)
    {
        return $Class::where($whereConditions)
            ->whereIsActive();
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
     * Get Model Builder from client request for normal query.
     *
     * @param string $uuid
     *
     * @return ArtisanCloudModel $object
     */
    public function getModelForClientByUUID(string $uuid)
    {
        $model = $this
            ->getModelsForClientByUUIDs([$uuid])
            ->first();

        return $model;
    }

    /**
     * Get Models Builder from client request for normal query.
     *
     * @param array $uuids
     *
     * @return Builder
     */
    public function getModelsForClientByUUIDs(array $uuids): Builder
    {
        $models = $this->m_model
            ->whereIn('uuid', $uuids)
            ->where('status', $this->m_model::STATUS_NORMAL);

        return $models;
    }


    /**
     * Get Model.
     *
     * @param string $uuid
     *
     * @return Model $model
     */
    public function getModelByUUID(string $uuid)
    {
        $model = $this->getModelsByUUIDs([$uuid])->first();

        return $model;
    }

    /**
     * Get Model Builder.
     *
     * @param string $uuid
     *
     * @return Builder
     */
    public function getModelsByUUIDs(array $uuids) : Builder
    {
        $models = $this->m_model->whereIn('uuid', $uuids);

        return $models;
    }





    /**--------------------------------------------------------------- Cache Query  -------------------------------------------------------------*/
    /**
     * Get Cached Model Builder from client request for normal query.
     *
     * @param string $uuid
     *
     * @return ArtisanCloudModel $cachedModel
     */
    public function getCachedModelForClientByUUID($uuid)
    {
        $cachedModel = $this->getCachedModelForClientByKey('uuid', $uuid);

        return $cachedModel;
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

            $model = static::GetBy( [
                $keyName => $keyValue,
                'status' => $className::STATUS_NORMAL
            ]);
//            dd(123, $model);

            return $model;
        });

        return $cachedModel;
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
        $cacheTag = $this->m_model->getCacheTag();
        $cacheKey = $this->m_model->getListCacheKey($para['page'], $para['perPage']);
//        dd($cacheTag, $cacheKey);
        $cachedList = Cache::tags($cacheTag)->remember($cacheKey, CacheTimeout::CACHE_TIMEOUT_MONTH, function () use ($para) {

            $this->logLocal('info', "request " . class_basename($this) . " list here page:{$para['page']}, perPage:{$para['perPage']}");

            $list = $this->getListForClient($para)->get();

            return $list;
        });

        return $cachedList;

    }


}