<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = ['name', 'priority_id'];

    protected $hidden = ['updated_at', 'deleted_at'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'needs_to_start_by' => 'datetime:Y-m-d',
            'needs_to_deployed_by' => 'datetime:Y-m-d',
            'created_at' => 'datetime:Y-m-d',
        ];
    }

    /**
     * Project's Studios.
     *
     * @return void
     */
    public function studios()
    {
        return $this->belongsToMany(Studio::class);
    }

    /**
     * Project's release.
     *
     * @return void
     */
    public function releases()
    {
        return $this->belongsToMany(Release::class);
    }

    /**
     * Organization's priority.
     *
     * @return void
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Project's priority.
     *
     * @return void
     */
    public function priority()
    {
        return $this->belongsTo(Priority::class);
    }

    // /**
    //  * Project blockers.
    //  *
    //  * @return void
    //  */
    // public function blockers()
    // {
    //     return $this->hasMany(Blocker::class);
    // }

    /**
     * Project blockers.
     *
     * @return Illuminate\Database\Eloquent\Concerns\HasRelationships::hasMany
     */
    public function meetings()
    {
        return $this->hasMany(Meeting::class);
    }

    // /**
    //  * Get all of the post's comments.
    //  */
    // public function comments(): MorphMany
    // {
    //     return $this->morphMany(Comment::class, 'commentable');
    // }
}
