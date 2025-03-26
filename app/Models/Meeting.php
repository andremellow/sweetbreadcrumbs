<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Meeting extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = ['name', 'description', 'date'];

    /**
     * The attributes hidden from json.
     *
     * @var string[]
     */
    protected $hidden = ['updated_at', 'deleted_at'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        $format = config('app.save_date_format');

        return [
            'date' => "date:$format",
            'created_at' => 'date',
        ];
    }

    public function workstream(): BelongsTo
    {
        return $this->belongsTo(Workstream::class);
    }

    // /**
    //  * Get all of the post's comments.
    //  */
    // public function comments(): MorphMany
    // {
    //     return $this->morphMany(Comment::class, 'commentable');
    // }
}
