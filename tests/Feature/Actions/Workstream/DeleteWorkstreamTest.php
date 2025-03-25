<?php

use App\Actions\Workstream\DeleteWorkstream;
use App\DTO\Workstream\DeleteWorkstreamDTO;
use App\Models\Workstream;

beforeEach(function () {
    [$user, $organization] = createOrganization();
    $this->user = $user;
    $this->organization = $organization;
});

it('Soft deletes a meeting', function () {
    $workstream = Workstream::factory()->for($this->organization)->withPriority($this->organization)->create();
    expect($workstream->deleted_at)->toBeNull();

    app(DeleteWorkstream::class)(
        new DeleteWorkstreamDTO($workstream)
    );

    $workstream->refresh();

    expect($workstream->deleted_at)->not->toBeNull();
});
