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
        Schema::create('priorities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('order')->default(1);
            $table->foreignId('organization_id')->nullable()->constrained();
            $table->timestamps();
            $table->softDeletes();
        });

        DB::table('priorities')->insert(['name' => 'Low', 'order' => 1, 'organization_id'=> 1]);
        DB::table('priorities')->insert(['name' => 'Mid', 'order' => 10, 'organization_id'=> 1]);
        DB::table('priorities')->insert(['name' => 'High', 'order' => 20, 'organization_id'=> 1]);
        DB::table('priorities')->insert(['name' => 'Urgent', 'order' => 30, 'organization_id'=> 1]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('priorities');
    }
};
