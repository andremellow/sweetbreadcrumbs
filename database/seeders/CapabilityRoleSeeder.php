<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CapabilityRoleSeeder extends Seeder
{
    public function run(): void
    {
        foreach (RoleEnum::cases() as $role) {

            foreach ($role->capabilities() as $capability) {
                // Check if the capability-role association already exists
                if (! DB::table('capability_role')
                    ->where('role_id', $role->value)
                    ->where('capability_id', $capability->value)
                    ->exists()
                ) {
                    // Insert capability-role association
                    DB::table('capability_role')->insert([
                        'role_id' => $role->value,
                        'capability_id' => $capability->value,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

    }
}
