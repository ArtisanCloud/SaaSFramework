<?php
declare(strict_types=1);

namespace ArtisanCloud\SaaSFramework\Services;


use ArtisanCloud\SaaSFramework\Models\ArtisanCloudModel;
use Illuminate\Database\Query\Builder;

class ArtisanCloudService
{
    protected $m_model;

    const PAGE_DEFAULT = 1;
    const PER_PAGE_DEFAULT = 10;

    public function __construct()
    {

    }

    public static function GetBy($Class, $key, $value)
    {
        return $Class::where($key, $value)->first();
    }

    public function makeBy($arrayData)
    {
        return $this->m_model;
    }

    public function createBy($arrayData)
    {
        $model = $this->makeBy($arrayData);

        $bResult = $model->save();

        return $bResult ? $model : null;
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
    public function getListForClient($para=[])
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
     * @return Product $cachedDetail
     */
    public function getCachedDetailForClientByUUID($uuid)
    {
        $cacheTag = $this->getCacheTag();
        $cacheKey = $this->getItemCacheKey($uuid);
//        dd($cacheTag, $cacheKey);
        $cachedDetail = Cache::tags($cacheTag)->remember($cacheKey, SYSTEM_CACHE_TIMEOUT, function () use ($uuid) {

            $this->logLocal('info', "request " . class_basename($this) . " detail here, uuid:{$uuid} .");

            $detail = $this->getDetailForClientByUUID($uuid);

            return $detail;
        });

        return $cachedDetail;
    }




    /**
     * Get Model Builder from client request for normal query.
     *
     * @param string $uuid
     *
     * @return ArtisanCloudModel $object
     */
    public function getDetailForClientByUUID($uuid)
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
    public function getDetailByUUID($uuid)
    {
        $detail = $this->m_model->where([
            'uuid' => $uuid,
        ])->first();

        return $detail;
    }

}