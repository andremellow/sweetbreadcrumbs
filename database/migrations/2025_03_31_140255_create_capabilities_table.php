<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('capabilities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('group');
            $table->timestamps();
            $table->softDeletes();
        });

        // Run the seeder
        Artisan::call('db:seed', [
            '--class' => 'CapabilitySeeder', // Replace with your seeder class name
            '--force' => true, // Force seeding in production
        ]);

    }

    public function down(): void
    {
        Schema::dropIfExists('capabilities');
    }
};
