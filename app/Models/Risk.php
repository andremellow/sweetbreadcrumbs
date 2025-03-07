<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Risk extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = ['id', 'name'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'raised_at' => 'date',
            'resolved_at' => 'date',
        ];
    }

    /**
     * Risk Status.
     *
     * @return void
     */
    public function riskStatus()
    {
        return $this->belongsTo(RiskStatus::class);
    }

    /**
     * Risk Level.
     *
     * @return void
     */
    public function riskLevel()
    {
        return $this->belongsTo(RiskLevel::class);
    }

    /**
     * Risk Probability.
     *
     * @return void
     */
    public function probability()
    {
        return $this->belongsTo(Probability::class);
    }
}
