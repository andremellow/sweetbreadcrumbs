<?php

use Laravel\Dusk\Browser;

test('it validates the fields', function () {
    [$user, $organization] = createOrganization(name: 'SBC');

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
