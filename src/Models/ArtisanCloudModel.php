<?php
declare(strict_types=1);

namespace ArtisanCloud\SaaSFramework\Models;

use ArtisanCloud\SaaSFramework\Traits\Cacheable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use ArtisanCloud\Taggable\Traits\Taggable;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\Types\This;


class ArtisanCloudModel extends Model
{
    use HasFactory, Cacheable, Taggable;

    const TABLE_NAME = '';

    const STATUS_INIT = 0;          // init
    const STATUS_NORMAL = 1;        // normal
    const STATUS_INVALID = 4;       // soft deleted

    const PAGE_DEFAULT = 1;
    const PER_PAGE_DEFAULT = 10;

    protected $connection = 'pgsql';
    protected $table = self::TABLE_NAME;

    protected $primaryKey = 'uuid';
    protected $keyType = 'string';
    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $keyName = $model->getKeyName();
            if($keyName==='uuid'){
                $model->{$keyName} = (string)Str::uuid();
            }
        });
    }

    /**--------------------------------------------------------------- schema functions  -------------------------------------------------------------*/
    public static function getPrimaryKeyName()
    {
        return (new static())->getKeyName();
    }

    public static function getConnectionNameStatic()
    {
        return (new static())->getConnectionName();
    }

    public function getForeignKey()
    {
        return $this->getTransformForeignKey(Str::snake(class_basename($this)));
    }

    public function getTransformForeignKey($key)
    {
        return $key . '_' . $this->getKeyName();
    }



    /**--------------------------------------------------------------- condition functions  -------------------------------------------------------------*/
    public function scopeWhereIsActive($query)
    {
        return $query->where('status', $this::STATUS_NORMAL);
    }

}
