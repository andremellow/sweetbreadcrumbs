<?php

use App\Actions\Organization\CreateOrganization;
use App\DTO\Organization\CreateOrganizationDTO;
use App\Enums\EventEnum;
use App\Livewire\Organization\Invite;
use App\Models\User;
use App\Services\InviteService;
use App\Services\OrganizationService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Livewire\Livewire;

beforeEach(function () {
    // Create test user and organization
    $this->user = User::factory()->create();
    $this->organization = (new CreateOrganization)($this->user, new CreateOrganizationDTO('new organization'));
    $this->invites = App\Models\Invite::factory(10)->for($this->organization)->for($this->user, 'inviter')->withRole($this->organization)->create();

    URL::defaults(['organization' => $this->organization->slug]);

    app()->bind(OrganizationService::class, function () {
        return new OrganizationService(
            app(CreateOrganization::class),
            $this->organization
        );
    });
});

afterEach(function () {
    Mockery::close();
});

it('renders the Invite component successfully', function () {
    Livewire::actingAs($this->user)
        ->test(Invite::class)
        ->assertStatus(200)
        ->assertSee('Email')
        ->assertSee('Role')
        ->assertSeeHtml('Send');
});

it('list invites', function () {
    // TODO: Maybe move this to a diffent method and test only the WithSort Trait
    Livewire::actingAs($this->user)
        ->test(Invite::class)
        ->assertSee($this->invites[0]->email)
        ->assertSee($this->invites[0]->sent_at->toDayDateTimeString())
        ->assertSee($this->invites[0]->role->name)
        ->assertSee($this->invites[9]->email)
        ->assertSee($this->invites[9]->sent_at->toDayDateTimeString())
        ->assertSee($this->invites[9]->role->name);
});

it('validates', function () {
    Livewire::actingAs($this->user)
        ->test(Invite::class)
        ->set('role_id', null)
        ->call('send')
        ->assertHasErrors([
            'email' => ['The email field is required.'],
            'role_id' => ['The role id field is required.'],
        ])
        ->set('role_id', 5)
        ->set('email', $this->invites[0]->email)
        ->call('send')
        ->assertHasErrors([
            'email' => ["{$this->invites[0]->email} was already invited"],
        ]);
});

it('created a invite', function () {
    Notification::fake();
    $invite = $this->organization->invites()->where('email', 'johndoe@gmail.com')->first();
    expect($invite)->toBeNull();

    $date = Carbon::now();
    Livewire::actingAs($this->user)
        ->test(Invite::class)
        ->set('email', 'johndoe@gmail.com')
        ->call('send')
        ->assertDispatched(EventEnum::INVITE_CREATED->value);

    $invite = $this->organization->invites()->where('email', 'johndoe@gmail.com')->first();

    expect($invite->email)->toBe('johndoe@gmail.com');
});

it('cancels a event successfully and dispatches event', function () {
    $inviteToDelete = $this->invites->first();

    Livewire::actingAs($this->user)
        ->test(Invite::class)
        ->call('delete', app(OrganizationService::class), app(InviteService::class), $inviteToDelete->id)
        ->assertDispatched(EventEnum::INVITE_DELETED->value, inviteId: $inviteToDelete->id);

    $inviteToDelete = App\Models\Invite::find($inviteToDelete->id);

    expect($inviteToDelete)->toBeNull();
});

it('resends a event successfully and dispatches event', function () {
    $inviteToUpdate = $this->invites->first();
    $inviteToUpdate->update(['sent_at' => null]);
    Livewire::actingAs($this->user)
        ->test(Invite::class)
        ->call('resend', app(OrganizationService::class), app(InviteService::class), $inviteToUpdate->id);

    $inviteToUpdate->refresh();

    expect($inviteToUpdate->sent_at->toDateTimeString())->toBe(Carbon::now()->toDateTimeString());
});

it('it does not resend if can_reset does not allow it ', function () {
    $inviteToUpdate = $this->invites->first();
    $date = Carbon::now()->addMinute(-3);
    $inviteToUpdate->update(['sent_at' => $date]);
    Livewire::actingAs($this->user)
        ->test(Invite::class)
        ->call('resend', app(OrganizationService::class), app(InviteService::class), $inviteToUpdate->id);

    $inviteToUpdate->refresh();

    expect($inviteToUpdate->sent_at->toDateTimeString())->toBe($date->toDateTimeString());
});
