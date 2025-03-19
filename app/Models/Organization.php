<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
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
    // public function studios()
    // {
    //     return $this->hasMany(Studio::class);
    // }

    /**
     * Organization's Workstreams.
     *
     * @return Illuminate\Database\Eloquent\Concerns\HasRelationships::hasMany
     */
    public function workstreams()
    {
        return $this->hasMany(Workstream::class);
    }

    /**
     * Organization's Invites.
     *
     * @return Illuminate\Database\Eloquent\Concerns\HasRelationships::hasMany
     */
    public function invites()
    {
        return $this->hasMany(Invite::class);
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
     * Organization's Workstreams.
     *
     * @return Illuminate\Database\Eloquent\Concerns\HasRelationships::hasMany
     */
    public function probabilities()
    {
        return $this->hasMany(Probability::class);
    }

    /**
     * Organization's Roles.
     *
     * @return Illuminate\Database\Eloquent\Concerns\HasRelationships::hasMany
     */
    public function roles()
    {
        return $this->hasMany(Role::class);
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

    public function tasks()
    {
        return Task::whereHasMorph(
            'taskable',
            [Workstream::class], // Add more models here
            function ($query) {
                $query->where('organization_id', $this->id);
            }
        );
    }

    /**
     * Get all of the meetings for the organization.
     */
    public function meetings(): HasManyThrough
    {
        return $this->hasManyThrough(Meeting::class, Workstream::class);
    }
}
