<?php

use Laravel\Dusk\Browser;

test('it validates the fields', function () {
    [$user, $organization] = createOrganization(name: 'SBC');

    $this->browse(function (Browser $browser) use ($user) {
        $browser
            ->loginAs($user)
            ->visit(route('welcome.organization'))
            ->press('Create')
            ->waitForText('The name field is required.')
            ->type('name', 'SBC')
            ->press('Create')
            ->waitForText('The name has already been taken.')
            ->type('name', 'My New organization')
            ->press('Create')
            ->waitForRoute('welcome.workstream', ['organization' => 'my-new-organization']);
    });
});
