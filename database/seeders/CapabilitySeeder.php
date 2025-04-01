<?php

namespace Database\Seeders;

use App\Enums\CapabilityEnum;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CapabilitySeeder extends Seeder
{
    public function run(): void
    {
        foreach (CapabilityEnum::cases() as $capability) {
            if (! DB::table('capabilities')->where('id', $capability->value)->exists()) {
                DB::table('capabilities')->insert([
                    'id' => $capability->value,
                    'name' => Str::of($capability->name)->lower()->headline(), // Transform the name using Str::headline
                    'group' => $capability->group()->value,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

    }
}
