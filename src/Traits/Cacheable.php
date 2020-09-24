<?php
/**
 * Created by PhpStorm.
 * User: michaelhu
 * Date: 2019/7/23
 * Time: 12:07 PM
 */

namespace ArtisanCloud\SaaSFramework\Traits;




interface CacheTimeout
{
    const CACHE_TIMEOUT_DEFAULT = 60*60;
    const CACHE_TIMEOUT_MINUTE = 60;
    const CACHE_TIMEOUT_HOUR = 60*60;
    const CACHE_TIMEOUT_DAY = 60*60*24;
    const CACHE_TIMEOUT_MONTH = 60*60*24*30;
    const CACHE_TIMEOUT_SEASON = 60*60*24*30*3;
    const CACHE_TIMEOUT_YEAR = 60*60*24*30*3*12;
}

trait Cacheable
{

    /**
     * Get cache tag.
     *
     * @return string $cacheTag
     */
    public function getCacheTag()
    {
        $cacheTag = class_basename($this);

        return $cacheTag;
    }


    /**
     * Get list cache key
     *
     * @param  integer  $page
     * @param  integer  $perPage
     *
     * @return string $cacheTag
     */
    public function getListCacheKey($page, $perPage)
    {
        $cacheKey = is_null($page) ? '1' : "page.{$page}.perPage.{$perPage}";

        return $cacheKey;
    }

    /**
     * Get item cache key
     *
     * @param  string $key
     *
     * @return string $cacheTag
     */
    public function getItemCacheKey($key)
    {
        $cacheKey = "item.{$key}";

        return $cacheKey;
    }


    /**
     * Get item attribute cache key
     *
     * @param  string  $attribute
     *
     * @return string $cacheTag
     */
    public function getItemAttributeCacheKey($attribute)
    {
        $cacheKey =  "item.{$this->getId()}"
                    .".attribute.{$attribute}"
                    .".at.{$this->{$this->getUpdatedAtColumn()}->timestamp}";

        return $cacheKey;
    }


    /**
     * Flush by Tags
     *
     * @param  array | string  $tags
     *
     * @return string $cacheTag
     */
    public function flushByTags($tags=null)
    {
        \Cache::tags($tags)->flush();

    }

    /**
     * Flush by Tags
     *
     * @param  array | string  $tags
     * @param  array | string  $key
     *
     * @return string $cacheTag
     */
    public function forgetByTagsAndKeys($tags=null, $key=null)
    {
        return \Cache::tags($tags)->forget($key);

    }


}
