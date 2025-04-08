<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

interface AccessibleContract
{
    public function accesses(): MorphMany;

    public function organization(): BelongsTo;
}
