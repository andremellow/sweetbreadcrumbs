<?php

namespace Database\Seeders;

use App\Actions\Organization\CreateOrganization;
use App\DTO\Access\GrantAccessDTO;
use App\DTO\Organization\CreateOrganizationDTO;
use App\Enums\RoleEnum;
use App\Models\Meeting;
use App\Models\Task;
use App\Models\User;
use App\Models\Workstream;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Services\AccessService;
use App\Services\OrganizationService;
use App\Services\UserService;
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
        $organizationService = new OrganizationService(new UserService($userAndreMello), app(CreateOrganization::class));
        $organization = (new CreateOrganization)($userAndreMello, new CreateOrganizationDTO('Disney'), $organizationService);

        // $studioWounder = $organization->studios()->create(['name' => 'Wounder']);
        // $studioFantasy = $organization->studios()->create(['name' => 'Fantasy']);
        // $studioDream = $organization->studios()->create(['name' => 'Dream']);
        // $studioUni = $organization->studios()->create(['name' => 'Uni']);
        // $studioNautilus = $organization->studios()->create(['name' => 'Nautilus']);

        $userJonathanNammour = User::create(['first_name' => 'Jonathan', 'last_name' => 'Nammour', 'email' => 'jonathan.nammour@disney.com']);
        $userMitchThomas = User::create(['first_name' => 'Mitch', 'last_name' => 'Thomas', 'email' => 'mitch.thomas@disney.com']);

        $userKevinHaynes = User::create(['first_name' => 'Kevin', 'last_name' => 'Haynes', 'email' => 'kevin.haynes@disney.com']);
        $userBrendonHaynes = User::create(['first_name' => 'Brendon', 'last_name' => 'Haynes', 'email' => 'brendon.haynes@disney.com']);

        $userSandyLeon = User::create(['first_name' => 'Sandy', 'last_name' => 'Leon', 'email' => 'sandy.leon@disney.com']);
        $userWalterCojal = User::create(['first_name' => 'Walter', 'last_name' => 'Cojal', 'email' => 'walter.cojal@disney.com']);

        $organizationService->attachUser($organization, $userJonathanNammour, roleId: 1);
        $organizationService->attachUser($organization, $userMitchThomas, roleId: 1);
        $organizationService->attachUser($organization, $userKevinHaynes, roleId: 2);
        $organizationService->attachUser($organization, $userBrendonHaynes, roleId: 2);
        $organizationService->attachUser($organization, $userSandyLeon, roleId: 3);
        $organizationService->attachUser($organization, $userWalterCojal, roleId: 3);

        //
        //        $organization->releases()->create(['name' => '5.31']);
        //        $organization->releases()->create(['name' => '5.32']);
        //        $organization->releases()->create(['name' => '5.33']);
        //        $organization->releases()->create(['name' => '5.34']);
        //        $organization->releases()->create(['name' => '5.35']);
        //        $organization->releases()->create(['name' => '5.36']);

        $accessService = app(AccessService::class);
        Workstream::factory()->count(30)
            ->for($organization)
            ->withPriority($organization)
            ->create()
            ->each(function (Workstream $workstream) use ($accessService, $userAndreMello, $organizationService) {
                $accessService->grantAccess(new GrantAccessDTO(
                    accessible: $workstream,
                    user: $userAndreMello,
                    role: RoleEnum::ADMIN
                ), $organizationService);
            });

        $workstream = $organization->workstreams()->first();
        $workstream->update(['name' => 'AA First Workstream']);

        Meeting::factory()->count(30)->for($workstream)->create();
        Task::factory()->count(30)->for($workstream, 'taskable')->withPriority($organization)->create();
    }
}
