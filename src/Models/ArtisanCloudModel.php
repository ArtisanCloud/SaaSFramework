<?php
declare(strict_types=1);

namespace ArtisanCloud\SaaSFramework\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;


class ArtisanCloudModel extends Model
{
    const TABLE_NAME = '';

    const STATUS_INIT = 0;          // init
    const STATUS_NORMAL = 1;        // normal
    const STATUS_INVALID = 4;       // deleted

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
            $model->{$model->getKeyName()} = (string)Str::uuid();
        });
    }


    public function scopeWhereIsActive($query)
    {
        return $query->where('status', $this::STATUS_NORMAL);
    }

}
