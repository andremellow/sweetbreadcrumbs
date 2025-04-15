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

test('It grants access', function () {

    $this->browse(function (Browser $browser) {
        $id = $this->usersWithoutAccess[0]->id;

        info('user Id', [
            'id' => $id,
        ]);

        $roleId = $this->organization->users()->where('users.id', $this->usersWithoutAccess[0]->id)->first()->pivot->role_id;

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
            ->click("@autocomplete-$id")
            ->waitForLivewireToLoad()
            ->pause(1000)
            ->select('roleId', $roleId)
            ->press('@Add')
            ->waitForText('Access granted')
            ->waitForText($this->usersWithoutAccess[0]->first_name)
            ->waitForText($this->usersWithoutAccess[0]->last_name);

        expect($this->workstream->accesses()->where('user_id', $id)->first()->user->id)->toBe($id);
    });
});

test('It revokes access', function () {

    $this->browse(function (Browser $browser) {
        $id = $this->usersWithAccess[0]->id;

        $browser
            ->loginAs($this->user)
            ->visit(route('workstreams.dashboard', [
                'organization' => $this->organization->slug,
                'workstream' => $this->workstream,
            ]))
            ->press('@open-member-access-modal')
            ->waitForText('Manage Access')
            ->assertSee($this->usersWithAccess[0]->first_name)
            ->assertSee($this->usersWithAccess[0]->last_name)
            ->click("@revoke-$id")
//            ->waitForDialog()
//            ->screenshot('dialog')
            ->acceptDialog()
            ->waitForLivewireToLoad()
            ->waitForText('Access revoked')
            ->assertMissing($this->usersWithAccess[0]->first_name)
            ->assertMissing($this->usersWithAccess[0]->last_name);

        expect($this->workstream->accesses()->where('user_id', $id)->exists())->toBe(false);
    });
});
