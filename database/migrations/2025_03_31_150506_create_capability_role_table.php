<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('capability_role', function (Blueprint $table) {
            $table->id();
            $table->foreignId('capability_id')->constrained();
            $table->foreignId('role_id')->constrained();
            $table->timestamps();
        });

        // Run the seeder
        Artisan::call('db:seed', [
            '--class' => 'CapabilityRoleSeeder', // Replace with your seeder class name
            '--force' => true, // Force seeding in production
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('capability_role');
    }
};
