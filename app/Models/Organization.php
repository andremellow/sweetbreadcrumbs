<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organization extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = ['id', 'name', 'slug'];

    /**
     * Organization's Priorities.
     *
     * @return Illuminate\Database\Eloquent\Concerns\HasRelationships::hasMany
     */
    public function priorities()
    {
        return $this->hasMany(Priority::class);
    }

    /**
     * Organization's Releases.
     *
     * @return Illuminate\Database\Eloquent\Concerns\HasRelationships::hasMany
     */
    public function releases()
    {
        return $this->hasMany(Release::class);
    }

    /**
     * Organization's Studio.
     *
     * @return Illuminate\Database\Eloquent\Concerns\HasRelationships::hasMany
     */
    public function studios()
    {
        return $this->hasMany(Studio::class);
    }

    /**
     * Organization's Projects.
     *
     * @return Illuminate\Database\Eloquent\Concerns\HasRelationships::hasMany
     */
    public function projects()
    {
        return $this->hasMany(project::class);
    }

    /**
     * Organization's Risk Levels.
     *
     * @return Illuminate\Database\Eloquent\Concerns\HasRelationships::hasMany
     */
    public function riskLevels()
    {
        return $this->hasMany(RiskLevel::class);
    }

    /**
     * Organization Risk's Statuses.
     *
     * @return Illuminate\Database\Eloquent\Concerns\HasRelationships::hasMany
     */
    public function riskStatuses()
    {
        return $this->hasMany(RiskStatus::class);
    }

    /**
     * Organization's Projects.
     *
     * @return Illuminate\Database\Eloquent\Concerns\HasRelationships::hasMany
     */
    public function probabilities()
    {
        return $this->hasMany(Probability::class);
    }

    /**
     * Organization's Users.
     *
     * @return lluminate\Database\Eloquent\Concerns\HasRelationships::belongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
