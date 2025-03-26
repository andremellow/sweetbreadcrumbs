<?php

use App\Models\User;
use Laravel\Dusk\Browser;

test('it validates the fields', function () {
    $user = User::factory()->create();

    $this->browse(function (Browser $browser) use ($user) {
        $browser
            ->loginAs($user)
            ->visit(route('welcome.profile'))
            ->type('last_name', 'Mello')
            ->press('Update')
            ->waitForText('The first name field is required.');

    });
});

test('it saves profile and redirect to organization', function () {
    $user = User::factory()->create();

    $this->browse(function (Browser $browser) use ($user) {
        $browser
            ->loginAs($user)
            ->visit(route('welcome.profile'))
            ->type('first_name', 'Andre')
            ->type('last_name', 'Mello')
            ->press('Update')
            ->waitForRoute('welcome.organization');
    });
});
