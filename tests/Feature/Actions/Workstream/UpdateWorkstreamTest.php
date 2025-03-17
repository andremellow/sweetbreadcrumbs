<?php

use App\Actions\Organization\CreateOrganization;
use App\Actions\Workstream\UpdateWorkstream;
use App\DTO\Organization\CreateOrganizationDTO;
use App\DTO\Workstream\UpdateWorkstreamDTO;
use App\Models\Priority;
use App\Models\Release;
use App\Models\User;
use App\Models\Workstream;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->organization = (new CreateOrganization)($this->user, new CreateOrganizationDTO('New Organization Name'));
    $this->workstream = Workstream::factory()->for($this->organization)->withPriority($this->organization)->create();
});

it('updates a workstream with all fields', function () {
    $priority = $this->organization->priorities()->first() ?? Priority::factory()->for($this->organization)->create();
    $release = Release::factory()->for($this->organization)->create();

    $updatedWorkstream = app(UpdateWorkstream::class)(
        UpdateWorkstreamDTO::from([
            'organization' => $this->organization,
            'workstream_id' => $this->workstream->id,
            'name' => 'Updated Workstream Name',
            'priority_id' => $priority->id, // Priority ID
        ])
    );

    $updatedWorkstream->refresh();

    expect($updatedWorkstream->id)->toBe($this->workstream->id);
    expect($updatedWorkstream->organization_id)->toBe($this->organization->id);
    expect($updatedWorkstream->name)->toBe('Updated Workstream Name');
    expect($updatedWorkstream->priority_id)->toBe($priority->id);
});
