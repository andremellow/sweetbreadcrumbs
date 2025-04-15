<?php

namespace App\Traits;

use App\Models\Access;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait IsAccessible
{
    public function accesses(): MorphMany
    {
        return $this->morphMany(Access::class, 'accessible');
    }

    /**
     * Organization's priority.
     *
     * @return void
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
}
