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
        Schema::create('risk_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('organization_id')->nullable()->constrained();
            $table->timestamps();
            $table->softDeletes();
        });

        DB::table('risk_statuses')->insert(['name' => 'Raised', 'organization_id' => 1]);
        DB::table('risk_statuses')->insert(['name' => 'Open', 'organization_id' => 1]);
        DB::table('risk_statuses')->insert(['name' => 'In Analysis', 'organization_id' => 1]);
        DB::table('risk_statuses')->insert(['name' => 'Mitigated', 'organization_id' => 1]);
        DB::table('risk_statuses')->insert(['name' => 'Resolved', 'organization_id' => 1]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('risk_statuses');
    }
};
