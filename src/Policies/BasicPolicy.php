<?php

namespace ArtisanCloud\SaaSFramework\Policies;

use App\Models\User;
use ArtisanCloud\SaaSPolymer\Services\ArtisanService\src\Models\Artisan;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Database\Eloquent\Model;

class BasicPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the given portfolio can be owned by the user.
     *
     * @param User $user
     * @param Model $model
     * @return bool
     */
    public function owns(User $user, Model $model): bool
    {
//        dump($user->uuid, $model->created_by);
        return (
            $user->uuid === $model->created_by
            || $model->created_by === CREATED_BY_SYSTEM
        );
    }

    /**
     * Determine if the given portfolio can be accessed by the user.
     *
     * @param User $user
     * @param string $modelClass
     * @return bool
     */
    public function accesses(User $user): bool
    {
//        dd($this);
        return !is_null($user);
    }
}
