<?php

use App\Models\Organization;
use App\Models\User;
use App\Models\Workstream;
use App\Services\UserService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Context;

beforeEach(function () {
    [$user, $organization] = createOrganization();
    $this->user = $user;
    $this->organization = $organization;

    // Instantiate UserService with a user
    $this->userService = new UserService($this->user);
});

afterEach(function () {
    Mockery::close();
});

it('sets a user', function () {
    $newUser = User::factory()->create();

    expect($this->userService->setUser($newUser))->toBeInstanceOf(UserService::class);
    expect($this->userService->setUser($newUser))->not->toBe($this->user);
    expect($this->userService->getUser())->toBe($newUser);
});

it('sets an organization', function () {
    $newOrganization = Organization::factory()->create();
    $this->userService->setOrganization($newOrganization);

    expect($this->userService->getCurrentOrganization())->toBe($newOrganization);
});

it('checks if user has organizations', function () {
    expect($this->userService->hasOrganizations())->toBeTrue();

    // Create a new user without an organization
    $newUser = User::factory()->create();
    $newUserService = new UserService($newUser);

    expect($newUserService->hasOrganizations())->toBeFalse();
});

it('gets the current organization', function () {
    Context::add('current_organization', $this->organization);
    // Should return the first attached organization by default
    Context::add('current_organization', $this->organization);
    expect($this->userService->getCurrentOrganization()->id)->toBe($this->organization->id);

    // Set a different organization
    $newOrganization = Organization::factory()->hasAttached($this->user, ['role_id' => 1])->create();
    $this->userService->setOrganization($newOrganization);

    // Now it should return the newly set organization
    expect($this->userService->getCurrentOrganization())->toBe($newOrganization);
});

it('gets the current organization from session', function () {
    // Should return the first attached organization by default
    $request = Request::create(route('workstreams.index', ['organization' => $this->organization->slug]))
        ->setRouteResolver(fn () => new FakeRoute($this->organization));

    $startSession = app(StartSession::class);
    $startSession->handle($request, fn ($return) => new Response);
    app()->instance('request', $request);

    $request->session()->put('current_organization', $this->organization);

    expect($this->userService->getCurrentOrganization()->id)->toBe($this->organization->id);
});

it('gets all user organizations', function () {
    // Create another organization and attach it
    createOrganization($this->user);

    $organizations = $this->userService->getOrganizations();

    expect($organizations)->toHaveCount(2);
    expect($organizations[0])->toBeInstanceOf(Organization::class);
    expect($organizations[1])->toBeInstanceOf(Organization::class);
});

it('checks if the user has an organization', function () {
    // Create another organization and attach it
    expect($this->userService->hasOrganization(organizationId: 3))->toBe(false);

    createOrganization($this->user);

    expect($this->userService->hasOrganization(organizationId: 3))->toBe(true);
});

it('gets all workstreams from the current organization', function () {
    Context::add('current_organization', $this->organization);

    $factoryWorkstreams = Workstream::factory(4)->for($this->organization)->withPriority($this->organization)->create()->sortBy('name')->values();

    $workstreams = $this->userService->getWorkstreams();

    expect($workstreams)->toBeCollection();
    expect($workstreams)->toHaveCount(4);
    expect($workstreams[0]->name)->toBe($factoryWorkstreams[0]->name);
    expect($workstreams[1]->name)->toBe($factoryWorkstreams[1]->name);
    expect($workstreams[2]->name)->toBe($factoryWorkstreams[2]->name);
    expect($workstreams[3]->name)->toBe($factoryWorkstreams[3]->name);
});

it('gets all invites from the current user', function () {
    Context::add('current_organization', $this->organization);
    $invitee = User::factory()->create();
    [$otherUser, $otherOrganization] = createOrganization();
    $currentUserInvite = App\Models\Invite::factory()->for($this->organization)->for($this->user, 'inviter')->withRole($this->organization)->create([
        'email' => $invitee->email,
    ]);
    App\Models\Invite::factory()->for($otherOrganization)->for($otherUser, 'inviter')->withRole($otherOrganization)->create();
    $currentUserInvite1 = App\Models\Invite::factory()->for($otherOrganization)->for($this->user, 'inviter')->withRole($otherOrganization)->create([
        'email' => $invitee->email,
    ]);

    $this->userService = new UserService($invitee);
    $invites = $this->userService->getInvites();
    expect($invites)->toHaveCount(2);
    expect($invites[0]->organization->id)->toBe($this->organization->id);
    expect($invites[0]->id)->toBe($currentUserInvite->id);
    expect($invites[1]->organization->id)->toBe($otherOrganization->id);
    expect($invites[1]->id)->toBe($currentUserInvite1->id);
});

it('gets and invite by Id', function () {
    Context::add('current_organization', $this->organization);
    $invitee = User::factory()->create();
    $invite = App\Models\Invite::factory()->for($this->organization)->for($this->user, 'inviter')->withRole($this->organization)->create([
        'email' => $invitee->email,
    ]);

    $this->userService = new UserService($invitee);
    $inviteById = $this->userService->getInviteById($invite->id);

    expect($inviteById->organization->id)->toBe($this->organization->id);
    expect($inviteById->id)->toBe($invite->id);

});

it('gets and invite by Id validates if id belongs to the current user', function () {
    Context::add('current_organization', $this->organization);
    $invite = App\Models\Invite::factory()->for($this->organization)->for($this->user, 'inviter')->withRole($this->organization)->create();

    $this->userService->getInviteById($invite->id);
})->throws(ModelNotFoundException::class);

it('retrieves organization by slug', function () {
    $organizationBySlug = UserService::getOrganizationBySlug($this->user, $this->organization->slug);

    expect($organizationBySlug)->toBeInstanceOf(Organization::class);
    expect($organizationBySlug->id)->toBe($this->organization->id);

    // Test with a non-existent slug
    $notFound = UserService::getOrganizationBySlug($this->user, 'non-existent-slug');

    expect($notFound)->toBeNull();
});
