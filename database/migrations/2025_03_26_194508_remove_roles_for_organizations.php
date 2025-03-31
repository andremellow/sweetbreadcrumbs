<?php

use App\Livewire\Welcome\Organization;
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
        Role::where('organization_id', '!=', config('app.demo_organization_id'))->delete();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach (Organization::where('id', '!=', config('app.demo_organization_id') ) as $organization) {
            Role::create(['name' => 'Admin', 'organization_id' => $organization->id]);
            Role::create(['name' => 'Contributor', 'organization_id' => $organization->id, 'is_default' => true]);
            Role::create(['name' => 'Viewer', 'organization_id' => $organization->id]);
        }
    }
};
