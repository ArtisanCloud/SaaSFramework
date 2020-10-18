<?php
declare(strict_types=1);


namespace ArtisanCloud\SaaSFramework\Traits;


use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

trait Assignable
{
    /**
     * Get assigned to.
     *
     * @return BelongsTo
     *
     */
    public function assignedToUser()
    {
        return $this->belongsTo(User::class, 'assigned_to_user_uuid');
    }

    /**
     * Get assigned resources.
     *
     * @return HasMany
     *
     */
    public function assignedByResources()
    {
        return $this->hasMany(User::class, 'assigned_to_user_uuid');
    }
}
