<?php

use App\Models\Role;
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
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('is_default')->default(false);
            $table->foreignId('organization_id')->constrained();

            $table->softDeletes();
            $table->timestamps();
        });

        Role::create(['name' => 'Admin', 'organization_id' => 1]);
        Role::create(['name' => 'Contributor', 'organization_id' => 1, 'is_default' => true]);
        Role::create(['name' => 'Viewer', 'organization_id' => 1]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
