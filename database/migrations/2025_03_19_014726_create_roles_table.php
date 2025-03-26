<?php

use App\Models\Organization;
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

        foreach(Organization::all() as $organization) {
            Role::create(['name' => 'Admin', 'organization_id' => $organization->id]);
            Role::create(['name' => 'Contributor', 'organization_id' => $organization->id, 'is_default' => true]);
            Role::create(['name' => 'Viewer', 'organization_id' => $organization->id]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
