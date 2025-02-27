<?php

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
        Schema::create('risks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->text('mitigration')->nullable();
            $table->date('raised_at');
            $table->date('resolved_at')->nullable();
            $table->foreignId('risk_status_id')->constrained();
            $table->foreignId('risk_level_id')->constrained();
            $table->foreignId('probability_id')->constrained();
            $table->foreignId('project_id')->constrained();
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('risks');
    }
};
