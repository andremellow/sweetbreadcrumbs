<?php

use App\Actions\Access\GrantAccess;
use App\Actions\Access\RevokeAccess;
use App\DTO\Access\GrantAccessDTO;
use App\DTO\Access\RevokeAccessDTO;
use App\Enums\RoleEnum;
use App\Models\Access;
use App\Models\Invite;
use App\Models\User;
use App\Models\Workstream;
use App\Services\AccessService;
use App\Services\OrganizationService;
use App\Services\UserService;
use Illuminate\Support\Facades\Context;

covers(AccessService::class);

beforeEach(function () {
    [$user, $organization] = createOrganization();
    $this->user = $user;
    $this->organization = $organization;

    /** @var GrantAccess */
    $this->mockGrantAccess = Mockery::mock(GrantAccess::class);

    /** @var RevokeAccess */
    $this->mockRevokeAccess = Mockery::mock(RevokeAccess::class);

    /** @var OrganizationService */
    $this->mockOrganizationService = Mockery::mock(OrganizationService::class);

    /** @var UserService */
    $this->mockUserService = Mockery::mock(UserService::class);

    $this->userService = new UserService($this->user);

    $this->service = new AccessService(
        userService: $this->userService,
        grantAccess: $this->mockGrantAccess,
        revokeAccess: $this->mockRevokeAccess,
    );

    Context::add('current_organization', $this->organization);

});

afterEach(function () {
    Mockery::close();
});

it('creates a invite using CreateInvite action', function () {
    $accessServiceMock = Mockery::mock(AccessService::class)
        ->makePartial()  // Only mock specific methods, not the entire class
        ->shouldAllowMockingProtectedMethods();  // Allow mocking of protected methods

    // Inject the mocked CreateInvite into the InviteService constructor
    $accessServiceMock->__construct(
        userService: $this->mockUserService,
        grantAccess: $this->mockGrantAccess,
        revokeAccess: $this->mockRevokeAccess,
    );

    /** @var Access */
    $mockAccess = $this->mock(Access::class);

    // Mock __invoke
    $this->mockGrantAccess
        ->shouldReceive('__invoke')
        ->once()
        ->with(GrantAccessDTO::class, OrganizationService::class)
        ->andReturn($mockAccess);

    // Call the method
    $access = $accessServiceMock->grantAccess(
        new GrantAccessDTO(
            accessible: $this->mock(Workstream::class),
            user: $this->mock(User::class),
            role: \App\Enums\RoleEnum::ADMIN
        ), $this->mockOrganizationService
    );

    expect($access)->toBe($mockAccess);
});

it('Revokes access', function () {
    /** @var Invite */
    $dto = new RevokeAccessDTO(
        accessible: $this->mock(Workstream::class),
        user_id: 1,
    );

    // Expect the UpdateInvite action to be called with these parameters
    $this->mockRevokeAccess
        ->shouldReceive('__invoke')
        ->once()
        ->with($dto);

    // Call the method
    $this->service->revokeAccess($dto);

});

describe('list access', function () {
    beforeEach(function () {
        $this->workstream = Workstream::factory()->for($this->organization)->withPriority($this->organization)->create();

        [$usersWithAccess, $usersWithoutAccess] = createAccessUsers($this->organization, $this->workstream);
        $this->usersWithAccess = $usersWithAccess;
        $this->usersWithoutAccess = $usersWithoutAccess;

        $this->accessService = app(AccessService::class);
    });

    it('lists members', function () {
        $members = $this->accessService->list(
            accessible: $this->workstream,
        );

        expect($members)->toHaveCount(10);

        $idToFind = $this->usersWithAccess[0]->id;
        $idToFail = $this->usersWithoutAccess[0]->id;

        expect($members->find($idToFind)->id)->toBe($idToFind);
        expect($members->find($idToFail))->toBeNull();

    });

    it('lists non members to autocomplete', function () {
        // Make name unique
        $this->usersWithoutAccess[0]->update(['first_name' => 'John123', 'last_name' => 'Doe321', 'email' => 'johndoe@gmail.com']);

        $members = $this->accessService->listAutoComplete(
            accessible: $this->workstream,
            search: 'johndoe@gmail.com'
        );

        expect($members)->toHaveCount(1);

        expect($members[0]->id)->toBe($this->usersWithoutAccess[0]->id);

    });

    it('does not lists members already with access to autocomplete', function () {
        // Make name unique
        $this->usersWithAccess[0]->update(['first_name' => 'John123', 'last_name' => 'Doe321', 'email' => 'johndoe@gmail.com']);

        $members = $this->accessService->listAutoComplete(
            accessible: $this->workstream,
            search: 'johndoe@gmail.com'
        );

        expect($members)->toHaveCount(0);
    });

});
