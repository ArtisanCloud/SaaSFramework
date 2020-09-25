<?php
declare(strict_types=1);

namespace ArtisanCloud\SaaSFramework\Services;


use App\Services\UserService\UserService;
use ArtisanCloud\SaaSFramework\Models\ArtisanCloudModel;
use ArtisanCloud\SaaSFramework\Services\LandlordService\src\LandlordService;
use ArtisanCloud\SaaSFramework\Traits\CacheTimeout;
use ArtisanCloud\SaaSPolymer\Services\ArtisanService\src\ArtisanService;
use Illuminate\Database\Query\Builder;
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
     * Get model ArtisanCloudModel by key.
     *
     * @param array $whereConditions
     *
     * @return mixed
     */
    public static function GetBy($Class, array $whereConditions)
    {
        return static::GetItemsBy($Class, $whereConditions)->first();
    }

    /**
     * Get models ArtisanCloudModel by key.
     *
     * @param array $whereConditions
     *
     * @return Builder
     */
    public static function GetItemsBy($Class, array $whereConditions)
    {
        return $Class::where($whereConditions);
    }

    /**
     * Get model ArtisanCloudModel for client by key.
     *
     * @param array $whereConditions
     *
     * @return mixed
     */
    public static function GetForClientBy($Class, array $whereConditions)
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
     * @return Collection
     */
    public function getModelsForClientByUUIDs(array $uuids): Collection
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
     * @return Collection
     */
    public function getModelsByUUIDs(array $uuids)
    {
        $models = $this->m_model->whereIn('uuid', $uuids);

        return $models;
    }


    /**
     * Get children tree list with item id and specified the level count
     *
     * @param mixed $parent
     * @param int $status
     * @param array $attributes
     * @param int $level
     *
     * @return mixed
     */
    function getTreeList($parent = NULL, int $status = NULL, array $attributes, int $level = 5): ?array
    {
        // level counter --
        if ($level < 0) {
            return NULL;
        } else {
            $level = $level - 1;
        }


        // get parent node's children nodes
        $arrayModel = array();
        $qb = self::GetItemsBy(
            get_class($parent),
            ['parent_uuid' => ($parent ? $parent->uuid : null)]
        );
        if (is_null($status)) $qb->whereStatus($status);
        $collectionModel = $qb->get();
//    	dump($collectionChildren);


        // iterate children node with sub-children nodes
        foreach ($collectionModel as $key => $model) {
//      		dump($model);
            $model->load($attributes);
            $model->children = $this->getTreeList($model, $status, $attributes, $level);

            array_push($arrayModel, $model);
        }
        return $arrayModel;
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

            $model = static::GetBy($className, [
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

    /**
     * Get cached children tree list with item id and specified the level count
     *
     * @param mixed $parent
     * @param int $status
     * @param array $attributes
     * @param int $level
     *
     * @return mixed
     */
    function getCachedTreeList($parent = NULL, int $status = NULL, array $attributes, int $level = 5): ?array
    {
        $cacheTag = $this->m_model->getCacheTag();
        $cacheKey = $this->m_model->getItemCacheKey($parent->uuid . '.children');
//        dd($cacheTag, $cacheKey, CacheTimeout::CACHE_TIMEOUT_MONTH);
        $cachedList = Cache::tags($cacheTag)->remember($cacheKey, CacheTimeout::CACHE_TIMEOUT_MONTH, function () use ($parent, $status, $attributes, $level) {

            \Log::info("request " . class_basename($parent) . ":{$parent->uuid} tree list here status:{$status}, level:{$level}");
            $list = $this->getTreeList($parent, $status, $attributes, $level);

            return $list;
        });

        return $cachedList;
    }

}