<?php

use App\Actions\Organization\CreateOrganization;
use App\Actions\Workstream\DeleteWorkstream;
use App\DTO\Organization\CreateOrganizationDTO;
use App\DTO\Workstream\DeleteWorkstreamDTO;
use App\Models\User;
use App\Models\Workstream;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->organization = (new CreateOrganization)($this->user, new CreateOrganizationDTO('New Organization Name'));
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
