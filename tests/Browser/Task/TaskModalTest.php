<?php

use App\Models\Workstream;
use Laravel\Dusk\Browser;

test('Reloads listing when new task is created', function () {
    [$user, $organization] = createOrganization(name: 'SBC');

    $workstream = Workstream::factory()->for($organization)->withPriority($organization)->create();

    $this->browse(function (Browser $browser) use ($user, $organization, $workstream) {
        $browser
            ->loginAs($user)
            ->visit(route('workstreams.tasks.index', [
                'organization' => $organization->slug,
                'workstream' => $workstream,
            ]))
            ->press('Create task')
            ->waitForText('Small Steps, Big Wins—Let’s Tackle It!')
            ->type('@form.name', 'My new task')
            ->press('Save')
            ->waitUntilMissingText('Small Steps, Big Wins—Let’s Tackle It!')
            ->waitForText('My new task')
            ->responsiveScreenshots('list-tasks');
    });
});
