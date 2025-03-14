<?php

namespace App\Models;

use App\Enums\ConfigEnum;
use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = ['config_default_id', 'organization_id', 'value'];
    
    public function casts()
    {
        return [
            'key' => ConfigEnum::class,
        ];
    }
}
