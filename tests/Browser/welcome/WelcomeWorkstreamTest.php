<?php

use App\Actions\Organization\CreateOrganization;
use App\DTO\Organization\CreateOrganizationDTO;
use App\Models\User;
use Laravel\Dusk\Browser;

test('it validates the fields', function () {
    $user = User::factory()->create();
    $organization = (new CreateOrganization)($user, new CreateOrganizationDTO('SBC'));
    $workstream = null;
    $this->browse(function (Browser $browser) use ($user, $organization) {
        $browser
            ->loginAs($user)
            ->visit(route('welcome.workstream', ['organization' => $organization->slug]))
            ->press('Create')
            ->waitForText('The name field is required.')
            ->type('name', 'a')
            ->press('Create')
            ->waitForText('The name field must be at least 2 characters.')
            ->type('name', 'My Workstream 123')
            ->press('Create')
            ->waitForRoute('workstreams.dashboard', ['organization' => $organization->slug, 'workstream' => 1]);
    });
});
