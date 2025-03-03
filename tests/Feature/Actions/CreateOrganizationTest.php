<?php

use App\Actions\Organization\CreateOrganization;
use App\Models\Organization;
use App\Models\Priority;
use App\Models\Probability;
use App\Models\RiskLevel;
use App\Models\RiskStatus;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->organization = app(CreateOrganization::class)($this->user, 'New Organization Name');
});

it('creates a new organization', function () {
    // $this->organization = Organization::orderBy('id', 'desc')->first();
    expect($this->organization->name)->toBe('New Organization Name');
    expect($this->organization->slug)->toBe('new-organization-name');
});

it('slugs are unique', function () {
    $this->organization = app(CreateOrganization::class)($this->user, 'New Organization Name');
    expect($this->organization->name)->toBe('New Organization Name');
    expect($this->organization->slug)->toBe('new-organization-name-1');

    $this->organization = app(CreateOrganization::class)($this->user, 'New Organization Name');
    expect($this->organization->slug)->toBe('new-organization-name-2');
});

it('it attaches the user', function () {
    expect($this->organization->users()->first()->id)->toBe($this->user->id);
});

it('copies priorities', function () {
    expect($this->organization->priorities->count())->toBe(4);
    expect($this->organization->priorities->first()->name)->tobe(
        Priority::where(['organization_id' => config('app.demo_organization_id')])->first()->name
    );
});

it('copies risk levels', function () {
    expect($this->organization->riskLevels->count())->toBe(3);
    expect($this->organization->riskLevels->first()->name)->tobe(
        RiskLevel::where(['organization_id' => config('app.demo_organization_id')])->first()->name
    );
});

it('copies risk statuses', function () {
    expect($this->organization->riskStatuses->count())->toBe(5);
    expect($this->organization->riskStatuses->first()->name)->tobe(
        RiskStatus::where(['organization_id' => config('app.demo_organization_id')])->first()->name
    );
});

it('copies probabilities', function () {
    expect($this->organization->probabilities->count())->toBe(3);
    expect($this->organization->probabilities->first()->name)->tobe(
        Probability::where(['organization_id' => config('app.demo_organization_id')])->first()->name
    );
});
