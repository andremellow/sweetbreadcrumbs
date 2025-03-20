<?php

use App\Enums\ConfigEnum;
use App\Models\ConfigDefault;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        ConfigDefault::create(['id' => ConfigEnum::INVITE_EXPIRATION_IN_DAYS, 'value' => 7]);
        ConfigDefault::create(['id' => ConfigEnum::INVITE_RESENT_WAIT_IN_MINUTES, 'value' => 5]);

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        ConfigDefault::find(ConfigEnum::INVITE_EXPIRATION_IN_DAYS->value)->delete();

    }
};
