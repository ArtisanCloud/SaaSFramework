<?php

namespace ArtisanCloud\SaaSFramework\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;


class ArtisanCloudModel extends Model
{

    const STATUS_INIT = 0;          // init
    const STATUS_NORMAL = 1;        // normal
    const STATUS_INVALID = 4;       // deleted

    const PAGE_DEFAULT = 1;
    const PER_PAGE_DEFAULT = 10;

    protected $connection = 'pgsql';

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
     * Get list Builder from client request for normal query.
     * The function could be override by sub class for specific query.
     *
     * @param array $para
     *
     * @return Builder $listBuilder
     */
    public function getListForClient($para)
    {
        $para['status'] = $this::STATUS_NORMAL;

        $bPagination = $para['pagination'] ?? false;
        $page = $para['page'] ?? null;
        $perPage = $para['perPage'] ?? ArtisanCloudModel::getPerPage();

        $listBuilder = $this->getList($para, $page, $perPage);

        return $listBuilder;
    }


    /**
     * Get list Builder or Pagination  normal query.
     *
     * @param array $_arrayConditions
     * @param integer $_page
     * @param integer $_perPage
     *
     * @return mixed $listBuilder
     */
    public function getList($_arrayConditions = [],
                            $_page = NULL,
                            $_perPage = null)
    {
//        dd(request('page'), request('perPage'));

        $_page = $_page > 0 ? $_page : ( request('page')>0 ? request('page') : self::PAGE_DEFAULT );
        $_perPage = $_perPage > 0 ? $_perPage : ( request('perPage')>0 ? request('perPage') : self::PER_PAGE_DEFAULT );
//        dd($_page, $_perPage);

        $qb = $this->select('*');

//        dd($qb->toSql());
//        dump($_perPage, $_page);

        return $qb->limit($_perPage)->offset(($_page - 1) * $_perPage);

    }


    /**
     * Get Cached Model Builder from client request for normal query.
     * Cached duration by season
     *
     * @param integer $id
     *
     * @return Model $detail
     */
    public function getCachedDetailForClientByID($id)
    {
        $cacheTag = $this->getCacheTag();
        $cacheKey = $this->getItemCacheKey($id);
//        dd($cacheTag, $cacheKey);
        $cachedDetail = Cache::tags($cacheTag)->remember($cacheKey, SYSTEM_CACHE_TIMEOUT_SEASON, function () use ($id) {

            $this->logLocal('info', "request " . class_basename($this) . " detail here, id:{$id} .");

            $detail = $this->getDetailForClientByID($id);

            return $detail;
        });

        return $cachedDetail;
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
     * @param integer $id
     *
     * @return Model $detail
     */
    public function getDetailForClientByID($id)
    {
        $detail = $this->where([
            'id' => $id,
            'status' => $this::STATUS_NORMAL,
        ])->first();

        return $detail;
    }

    /**
     * Get Model Builder from client request for normal query.
     *
     * @param string $uuid
     *
     * @return Model $detail
     */
    public function getDetailForClientByUUID($uuid)
    {
        $detail = $this->where([
            'uuid' => $uuid,
            'status' => $this::STATUS_NORMAL,
        ])->first();

        return $detail;
    }

    /**
     * Get Model Builder from any request for normal query.
     *
     * @param integer $id
     *
     * @return Model $detail
     */
    public function getDetailByID($id)
    {
        $detail = $this->where([
            'id' => $id,
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
        $detail = $this->where([
            'uuid' => $uuid,
        ])->first();

        return $detail;
    }
}
