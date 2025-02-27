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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('priority_id')->nullable()->constrained();
            $table->text('release_plan')->nullable();
            $table->text('technical_documentation')->nullable();
            $table->date('needs_to_start_by')->nullable();
            $table->date('needs_to_deployed_by')->nullable();
            $table->unsignedBigInteger('toggle_on_by_release_id')->nullable();
            $table->foreign('toggle_on_by_release_id')->references('id')->on('releases');
            $table->foreignId('organization_id')->nullable()->constrained();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
