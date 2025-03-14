<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = ['name', 'description', 'priority_id', 'due_date', 'completed_at'];

    /**
     * The attributes hidden from json.
     *
     * @var string[]
     */
    protected $hidden = ['updated_at', 'deleted_at'];

    /**
     * The attributes hidden from json.
     *
     * @var string[]
     */
    protected $appends = ['is_completed', 'is_late'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        $format = config('app.save_date_format');

        return [
            'priority_id' => "integer",
            'due_date' => "date:$format",
            'completed_at' => "date:$format",
            'created_at' => 'date',
        ];
    }

    protected function getIsCompletedAttribute(): bool
    {
        return $this->completed_at !== null;
    }

    protected function getIsLateAttribute(): bool
    {
        return $this->completed_at === null && $this->due_date && $this->due_date->isPast();
    }

    /**
     * Get the parent taskable model.
     */
    public function taskable(): MorphTo
    {
        return $this->morphTo();
    }

    // /**
    //  * Task Project.
    //  *
    //  * @return BelongsTo
    //  */
    // public function project(): BelongsTo
    // {
    //     return $this->belongsTo(Project::class);
    // }

    /**
     * Task Priority.
     *
     * @return BelongsTo
     */
    public function priority(): BelongsTo
    {
        return $this->belongsTo(Priority::class);
    }
}
