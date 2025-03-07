<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('probabilities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('organization_id')->nullable()->constrained();
            $table->timestamps();
            $table->softDeletes();
        });

        DB::table('probabilities')->insert(['name' => 'Low', 'organization_id' => 1]);
        DB::table('probabilities')->insert(['name' => 'Mid', 'organization_id' => 1]);
        DB::table('probabilities')->insert(['name' => 'High', 'organization_id' => 1]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('probabilities');
    }
};
