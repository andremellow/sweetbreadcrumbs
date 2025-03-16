<?php

use App\Actions\Organization\CreateOrganization;
use App\Actions\Workstream\CreateWorkstream;
use App\DTO\Organization\CreateOrganizationDTO;
use App\DTO\Workstream\CreateWorkstreamDTO;
use App\Models\Release;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->organization = (new CreateOrganization)($this->user, new CreateOrganizationDTO('New Organization Name'));
});

it('creates a new workstream with required with all fields', function () {
    $priority = $this->organization->priorities()->first();
    $release = Release::factory()->for($this->organization)->create();

    $workstream = app(CreateWorkstream::class)(
        CreateWorkstreamDTO::from([
            'organization' => $this->organization,
            ...[
                'name' => 'This is a test workstream',
                'priority_id' => $priority->id,
            ],
        ])
    );

    $workstream->refresh();

    expect($workstream->organization_id)->toBe($this->organization->id);
    expect($workstream->name)->toBe('This is a test workstream');
    expect($workstream->priority_id)->toBe($priority->id);
});
