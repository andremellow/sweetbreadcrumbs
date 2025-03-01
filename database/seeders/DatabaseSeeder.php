<?php

namespace Database\Seeders;

use App\Models\Organization;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $organizationDemo = Organization::create(['id' => 1, 'name' => 'Demo Organization', 'slug' => 'demo-organization']);
        DB::statement('ALTER SEQUENCE organizations_id_seq RESTART WITH 2;');

        $organizationDemo->priorities()->create(['name' => 'Low', 'order' => 1]);
        $organizationDemo->priorities()->create(['name' => 'Mid', 'order' => 10]);
        $organizationDemo->priorities()->create(['name' => 'High', 'order' => 20]);
        $organizationDemo->priorities()->create(['name' => 'Urgent', 'order' => 30]);

        $organizationDemo->riskStatuses()->create(['name' => 'Raised']);
        $organizationDemo->riskStatuses()->create(['name' => 'Open']);
        $organizationDemo->riskStatuses()->create(['name' => 'In Analysis']);
        $organizationDemo->riskStatuses()->create(['name' => 'Mitigated']);
        $organizationDemo->riskStatuses()->create(['name' => 'Resolved']);

        $organizationDemo->riskLevels()->create(['name' => 'Low']);
        $organizationDemo->riskLevels()->create(['name' => 'Mid']);
        $organizationDemo->riskLevels()->create(['name' => 'High']);

        $organizationDemo->probabilities()->create(['name' => 'Low']);
        $organizationDemo->probabilities()->create(['name' => 'Mid']);
        $organizationDemo->probabilities()->create(['name' => 'High']);

    }
}
