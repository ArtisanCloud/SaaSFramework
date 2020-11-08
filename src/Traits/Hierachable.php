<?php
declare(strict_types=1);


namespace ArtisanCloud\SaaSFramework\Traits;

use ArtisanCloud\SaaSFramework\Services\ArtisanCloudService;
use Illuminate\Support\Facades\Cache;

trait Hierachable
{

    public function setParentField(string $strParentUUID = null)
    {
        $this->parentUUID = $strParentUUID ?? 'parent_uuid';
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
        $qb = ArtisanCloudService::GetItemsBy(
            [$this->parentUUID => ($parent ? $parent->uuid : null)],
            $this
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
        $cacheTag = $this->getCacheTag();
        $cacheKey = $this->getItemCacheKey($parent->uuid . '.children');
//        dd($cacheTag, $cacheKey, CacheTimeout::CACHE_TIMEOUT_MONTH);
        $cachedList = Cache::tags($cacheTag)->remember($cacheKey, CacheTimeout::CACHE_TIMEOUT_MONTH, function () use ($parent, $status, $attributes, $level) {

            \Log::info("request " . class_basename($parent) . ":{$parent->uuid} tree list here status:{$status}, level:{$level}");
            $list = $this->getTreeList($parent, $status, $attributes, $level);

            return $list;
        });

        return $cachedList;
    }

}