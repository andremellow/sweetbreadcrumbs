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
        $users = User::Factory(5)->create();
        $organizationService = app(OrganizationService::class);
        $organizationService->setOrganization($this->organization);
        $workstream = Workstream::factory()->for($this->organization)->create();

        $users->each(function (User $user) use($organizationService) {
            $organizationService->attachUser(
                organization: $this->organization,
                user: $user,
                roleId: RoleEnum::CONTRIBUTOR->value
            );


        })


    });

//    it('lists invites default sort by sent_at if invalid argument is given', function () {
//        $invites = $this->service->list(
//            organization: $this->organization,
//            sortBy: 'any_invalid_sort_fields',
//            sortDirection: SortDirection::ASC
//        );
//
//        expect($invites)->toHaveCount(3);
//        expect($invites[0]->email)->toBe('williamdoe@test.com');
//    });
//
//    it('lists invites with default sorting', function () {
//        $invites = $this->service->list(
//            organization: $this->organization
//        );
//
//        expect($invites)->toHaveCount(3);
//        expect($invites[0]->email)->toBe('williamdoe@test.com');
//        expect($invites[1]->email)->toBe('mariodoe@test.com');
//        expect($invites[2]->email)->toBe('andredoe@test.com');
//    });
//
//    it('lists invites with email sorting', function () {
//        $invites = $this->service->list(
//            organization: $this->organization,
//            sortBy: 'email'
//        );
//
//        expect($invites)->toHaveCount(3);
//        expect($invites[0]->email)->toBe('andredoe@test.com');
//        expect($invites[1]->email)->toBe('mariodoe@test.com');
//        expect($invites[2]->email)->toBe('williamdoe@test.com');
//    });
//
//    it('lists invites with role sorting', function () {
//        $invites = $this->service->list(
//            organization: $this->organization,
//            sortBy: 'role'
//        );
//
//        expect($invites)->toHaveCount(3);
//        expect($invites[0]->email)->toBe('mariodoe@test.com');
//        expect($invites[1]->email)->toBe('andredoe@test.com');
//        expect($invites[2]->email)->toBe('williamdoe@test.com');
//    });
 });
