<?php
declare(strict_types=1);


namespace ArtisanCloud\SaaSFramework\Traits;


use ArtisanCloud\Commentable\Models\Comment;

trait Commentable
{

    /**
     * Get assigned to.
     *
     * @return BelongsTo
     *
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
