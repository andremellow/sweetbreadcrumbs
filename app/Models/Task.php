<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [ 'completed_at' ];
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
            'due_date' => "date:$format",
            'created_at' => 'date',
        ];
    }

    protected function isCompleted(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $attributes['completed_at'] !== null,
        );
    }

    protected function isLate(): Attribute
    {
        
        return Attribute::make(
            get: fn ($value, $attributes) => $attributes['completed_at'] === null && $this->due_date->isPast(),
        );
    }


    /**
     * Task Project.
     *
     * @return BelongsTo
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

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
