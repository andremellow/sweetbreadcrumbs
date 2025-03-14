<?php

namespace App\Models;

use App\Enums\ConfigEnum;
use Illuminate\Database\Eloquent\Model;

class ConfigDefault extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [ 'key', 'value' ];

    public function casts()
    {
        return [
            'key' => ConfigEnum::class
        ];
    }
}
