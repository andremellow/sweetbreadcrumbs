<?php

use App\Enums\ConfigEnum;
use App\Enums\PriorityEnum;
use App\Models\ConfigDefault;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('config_defaults', function (Blueprint $table) {
            $table->id();
            $table->string('key');
            $table->text('value')->nullable();
            $table->timestamps();
        });

        ConfigDefault::create([ 'key' => ConfigEnum::TASK_DEFAULT_PRIORITY_ID, 'value' => PriorityEnum::MIDIUM ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('config_defaults');
    }
};
