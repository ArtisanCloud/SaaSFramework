<?php
declare(strict_types=1);

namespace ArtisanCloud\SaaSFramework\Models;

use App\Models\User;
use App\Services\UserService\UserService;
use ArtisanCloud\SaaSFramework\Traits\Cacheable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use ArtisanCloud\Taggable\Traits\Taggable;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
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
            if ($keyName === 'uuid') {
                $model->{$keyName} = (string)Str::uuid();
            }

            if ($model->hasAttribute('created_by')) {
                $user = UserService::getAuthUser();
                $model->created_by = $user ? $user->uuid : CREATED_BY_SYSTEM;
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

    public function hasAttribute($column)
    {
        // setup user
        return Schema::hasColumn($this->getTable(), $column);

    }


    /**--------------------------------------------------------------- condition functions  -------------------------------------------------------------*/
    public function scopeWhereIsActive($query)
    {
        return $query->where('status', $this::STATUS_NORMAL);
    }


    /**--------------------------------------------------------------- relation functions  -------------------------------------------------------------*/
    /**
     * Get creator.
     *
     * @return BelongsTo
     *
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }


}
