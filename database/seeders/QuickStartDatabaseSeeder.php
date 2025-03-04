<?php

namespace Database\Seeders;

use App\Actions\Organization\CreateOrganization;
use App\DTO\Organization\CreateOrganizationDTO;
use App\Models\Meeting;
use App\Models\Project;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class QuickStartDatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $userAndreMello = User::create(['first_name' => 'Andre', 'last_name' => 'Mello', 'email' => 'andre.mello@disney.com', 'password' => Hash::make('123456')]);
        $organization = (new CreateOrganization)($userAndreMello, new CreateOrganizationDTO('Disney'));

        $studioWounder = $organization->studios()->create(['name' => 'Wounder']);
        $studioFantasy = $organization->studios()->create(['name' => 'Fantasy']);
        $studioDream = $organization->studios()->create(['name' => 'Dream']);
        $studioUni = $organization->studios()->create(['name' => 'Uni']);
        $studioNautilus = $organization->studios()->create(['name' => 'Nautilus']);

        $userJonathanNammour = $organization->users()->create(['first_name' => 'Jonathan Nammour', 'email' => 'jonathan.nammour@disney.com']);
        $userMitchThomas = $organization->users()->create(['first_name' => 'Mitch Thomas', 'email' => 'mitch.thomas@disney.com']);

        $userKevinHaynes = $organization->users()->create(['first_name' => 'Kevin Haynes', 'email' => 'kevin.haynes@disney.com']);
        $userBrendonHaynes = $organization->users()->create(['first_name' => 'Brendon Haynes', 'email' => 'brendon.haynes@disney.com']);

        $userSandyLeon = $organization->users()->create(['first_name' => 'Sandy Leon', 'email' => 'sandy.leon@disney.com']);
        $userWalterCojal = $organization->users()->create(['first_name' => 'Walter Cojal', 'email' => 'walter.cojal@disney.com']);

        $studioWounder->users()->sync([$userAndreMello, $userJonathanNammour, $userMitchThomas, $userKevinHaynes, $userBrendonHaynes]);
        $studioUni->users()->sync([$userAndreMello, $userJonathanNammour]);
        $studioFantasy->users()->sync([$userSandyLeon]);
        $studioDream->users()->sync([$userWalterCojal]);

        $organization->releases()->create(['name' => '5.31']);
        $organization->releases()->create(['name' => '5.32']);
        $organization->releases()->create(['name' => '5.33']);
        $organization->releases()->create(['name' => '5.34']);
        $organization->releases()->create(['name' => '5.35']);
        $organization->releases()->create(['name' => '5.36']);

        Project::factory()->count(30)->for($organization)->create();

        $project = $organization->projects()->first();

        Meeting::factory()->count(30)->for($project)->create();
    }
}
