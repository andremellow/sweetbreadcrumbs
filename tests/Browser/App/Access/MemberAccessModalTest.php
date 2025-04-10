<?php

use App\Models\Workstream;
use Laravel\Dusk\Browser;


beforeEach(function () {
    [$user, $organization] = createOrganization(name: 'SBC');
    $this->user = $user;
    $this->organization = $organization;

    $this->workstream = Workstream::factory()->for($this->organization)->withPriority($this->organization)->create();

    [$usersWithAccess, $usersWithoutAccess] = createAccessUsers($this->organization, $this->workstream);
    $this->usersWithAccess = $usersWithAccess;
    $this->usersWithoutAccess = $usersWithoutAccess;
});

test('Opens access modal', function () {

    $this->browse(function (Browser $browser) {
        $browser
            ->loginAs($this->user)
            ->visit(route('workstreams.dashboard', [
                'organization' => $this->organization->slug,
                'workstream' => $this->workstream,
            ]))
            ->press('@open-member-access-modal')
            ->waitForText('Manage Access')
            ->type('search', $this->usersWithoutAccess[0]->email)
            ->waitForText($this->usersWithoutAccess[0]->first_name)
            ->keys('', '{enter}')
            ->select('roleId')
            ->press('Add')
            ->waitForText($this->usersWithoutAccess[0]->first_name)
            ->waitForText($this->usersWithoutAccess[0]->last_name);

    });
});
