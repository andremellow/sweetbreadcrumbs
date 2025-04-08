<?php

namespace App\Models;

use App\Contracts\AccessibleContract;
use App\Traits\IsAccessible;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $name
 */
class Workstream extends Model implements AccessibleContract
{
    use HasFactory, IsAccessible, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = ['name', 'priority_id'];

    protected $hidden = ['updated_at', 'deleted_at'];

    protected $appends = ['identification'];

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

    public function getIdentificationAttribute(): string
    {
        return "Workstream: {$this->name}";
    }

    /**
     * Workstream's Studios.
     *
     * @return void
     */
    // public function studios()
    // {
    //     return $this->belongsToMany(Studio::class);
    // }

    /**
     * Workstream's release.
     *
     * @return void
     */
    // public function releases()
    // {
    //     return $this->belongsToMany(Release::class);
    // }



    /**
     * Workstream's priority.
     *
     * @return void
     */
    public function priority(): BelongsTo
    {
        return $this->belongsTo(Priority::class);
    }

    // /**
    //  * Workstream blockers.
    //  *
    //  * @return void
    //  */
    // public function blockers()
    // {
    //     return $this->hasMany(Blocker::class);
    // }

    /**
     * Workstream Meetings.
     *
     * @return Illuminate\Database\Eloquent\Concerns\HasRelationships::hasMany
     */
    public function meetings(): HasMany
    {
        return $this->hasMany(Meeting::class);
    }

    /**
     * Workstream's Tasks.
     *
     * @return MorphMany
     */
    public function tasks(): MorphMany
    {
        return $this->morphMany(Task::class, 'taskable');
    }

}
