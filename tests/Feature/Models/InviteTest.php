<?php

use App\Actions\Organization\CreateOrganization;
use App\DTO\Organization\CreateOrganizationDTO;
use App\Models\Invite;
use App\Models\Role;
use App\Models\User;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Context;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->organization = (new CreateOrganization)($this->user, new CreateOrganizationDTO('New Organization Name'));
    $this->invite = Invite::factory()->for($this->organization)->for($this->user, 'inviter')
        ->create(['email' => 'mariodoe@test.com', 'role_id' => 4, 'sent_at' => Carbon::now()->addDays(-2)]);

    Context::add('current_organization', $this->organization);

    $this->app->bind(UserService::class, function () {
        return new UserService($this->user);
    });

});

it('expires', function () {
    expect($this->invite->is_expired)->toBe(false);

    $this->invite->update(['sent_at' => Carbon::now()->addDays(-8)]);
    $this->invite->refresh();

    expect($this->invite->is_expired)->toBe(true);

    $this->invite->update(['sent_at' => null]);
    $this->invite->refresh();

    expect($this->invite->is_expired)->toBe(false);
});

it('checks it notification can be resend', function () {

    expect($this->invite->can_resend)->toBe(true);

    $this->invite->update(['sent_at' => Carbon::now()->addMinutes(-3)]);
    $this->invite->refresh();

    expect($this->invite->can_resend)->toBe(false);

    $this->invite->update(['sent_at' => null]);
    $this->invite->refresh();

    expect($this->invite->can_resend)->toBe(true);
});

it('has relationships', function () {

    expect($this->invite->organization->id)->toBe($this->organization->id);
    expect($this->invite->inviter->id)->toBe($this->user->id);
    expect($this->invite->role)->toBeInstanceOf(Role::class);
});
