<?php

use App\Actions\Invite\CreateInvite;
use App\Actions\Invite\DeleteInvite;
use App\Actions\Invite\UpdateInviteSent;
use App\Actions\Organization\CreateOrganization;
use App\DTO\Invite\CreateInviteDTO;
use App\DTO\Invite\DeleteInviteDTO;
use App\DTO\Invite\UpdateInviteSentDTO;
use App\DTO\Organization\CreateOrganizationDTO;
use App\Enums\SortDirection;
use App\Models\Invite;
use App\Models\User;
use App\Notifications\InviteCreatedNotification;
use App\Services\InviteService;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Context;
use Illuminate\Support\Facades\Notification;

covers(InviteService::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->organization = (new CreateOrganization)($this->user, new CreateOrganizationDTO('New Organization Name'));

    /** @var CreateInvite */
    $this->mockCreateInvite = Mockery::mock(CreateInvite::class);

    /** @var UpdateInviteSent */
    $this->mockUpdateInviteSent = Mockery::mock(UpdateInviteSent::class);

    /** @var DeleteInvite */
    $this->mockDeleteInvite = Mockery::mock(DeleteInvite::class);
    $this->service = new InviteService(
        createInvite: $this->mockCreateInvite,
        deleteInvite: $this->mockDeleteInvite,
        updateInviteSent: $this->mockUpdateInviteSent);

    $this->app->bind(UserService::class, function () {
        return new UserService($this->user);
    });

    Context::add('current_organization', $this->organization);

});

afterEach(function () {
    Mockery::close();
});

it('creates a meeting using CreateInvite action', function () {
    $inviteServiceMock = Mockery::mock(InviteService::class)
        ->makePartial()  // Only mock specific methods, not the entire class
        ->shouldAllowMockingProtectedMethods();  // Allow mocking of protected methods

    // Inject the mocked CreateInvite into the InviteService constructor
    $inviteServiceMock->__construct($this->mockCreateInvite,
        $this->mockDeleteInvite,
        $this->mockUpdateInviteSent);

    $mockInvite = $this->mock(Invite::class);

    // Mock __invoke
    $this->mockCreateInvite
        ->shouldReceive('__invoke')
        ->once()
        ->with(CreateInviteDTO::class)
        ->andReturn($mockInvite);

    $inviteServiceMock
        ->shouldReceive('sendNotification')
        ->once()
        ->with(User::class, $mockInvite);

    // Call the method
    $invite = $inviteServiceMock->create(
        new CreateInviteDTO(
            user: $this->user,
            organization: $this->organization,
            email: 'johndoe@gmail.com',
            role_id: 7
        )
    );

    expect($invite)->toBe($mockInvite);
});

it('deletes a meeting using DeleteInvite action', function () {
    /** @var Invite */
    $mockInvite = Mockery::mock(Invite::class);
    $dto = new DeleteInviteDTO(
        user: $this->user,
        organization: $this->organization,
        invite_id: 1
    );

    // Expect the UpdateInvite action to be called with these parameters
    $this->mockDeleteInvite
        ->shouldReceive('__invoke')
        ->once()
        ->with($dto);

    // Call the method
    $this->service->delete($dto);

});

it('sends notification', function () {
    Notification::fake();
    $invite = Invite::factory()->for($this->organization)->for($this->user, 'inviter')->withRole($this->organization)->create();

    $updateInviteSentDTO = new UpdateInviteSentDTO(
        user: $this->user,
        organization: $this->organization,
        invite_id: $invite->id
    );

    $this->mockUpdateInviteSent
        ->shouldReceive('__invoke')
        ->once()
        ->with(Mockery::on(function ($dto) use ($updateInviteSentDTO) {
            // Perform assertions inside the callback to ensure the DTO matches expected values
            return $dto->user->id === $updateInviteSentDTO->user->id
                && $dto->organization->id === $updateInviteSentDTO->organization->id
                && $dto->invite_id === $updateInviteSentDTO->invite_id;
        }));

    $this->service->sendNotification(
        user: $this->user,
        invite: $invite
    );

    Notification::assertSentTo(
        $invite,
        InviteCreatedNotification::class
    );
});

it('validates can_resend before sending', function () {
    Notification::fake();
    // 'sent_at' => Carbon::now() -> should not allow to send message again
    $invite = Invite::factory()->for($this->organization)
        ->for($this->user, 'inviter')
        ->withRole($this->organization)->create(['sent_at' => Carbon::now()]);

    $invite = Invite::find($invite->id); // make wasRecentlyCreated false

    expect(
        fn () => $this->service->sendNotification(
            user: $this->user,
            invite: $invite
        )
    )->toThrow(Exception::class, 'Invite 1 try to resent before the time allowed! Something wrong.');

    Notification::assertNothingSent();
});

it('sends notification using invite id', function () {
    $invite = Mockery::mock(Invite::class);
    $inviteServiceMock = Mockery::mock(InviteService::class)->makePartial();

    $inviteServiceMock
        ->shouldReceive('get')
        ->once()
        ->with($this->organization, 12)
        ->andReturn($invite);

    $inviteServiceMock
        ->shouldReceive('sendNotification')
        ->once()
        ->with(User::class, $invite);

    $inviteServiceMock->sendNotificationUsingId(
        user: $this->user,
        organization: $this->organization,
        id: 12
    );
});

it('get invite by id', function () {

    $invite = Invite::factory()->for($this->organization)->for($this->user, 'inviter')->withRole($this->organization)->create();

    $inviteFound = $this->service->get(
        organization: $this->organization,
        id: $invite->id
    );

    expect($invite->id)->toBe($inviteFound->id);
});

describe('list meetings', function () {
    beforeEach(function () {

        Invite::factory()->for($this->organization)->for($this->user, 'inviter')
            ->create(['email' => 'andredoe@test.com', 'role_id' => 5, 'sent_at' => Carbon::now()->addDays(3)]);
        Invite::factory()->for($this->organization)->for($this->user, 'inviter')
            ->create(['email' => 'mariodoe@test.com', 'role_id' => 4, 'sent_at' => Carbon::now()->addDays(2)]);
        Invite::factory()->for($this->organization)->for($this->user, 'inviter')
            ->create(['email' => 'williamdoe@test.com', 'role_id' => 6, 'sent_at' => Carbon::now()->addDays(1)]);
    });

    it('lists meetings default sort by sent_at if invalid argument is given', function () {
        $invites = $this->service->list(
            organization: $this->organization,
            sortBy: 'any_invalid_sort_fields',
            sortDirection: SortDirection::ASC
        );

        expect($invites)->toHaveCount(3);
        expect($invites[0]->email)->toBe('williamdoe@test.com');
    });

    it('lists meetings with default sorting', function () {
        $invites = $this->service->list(
            organization: $this->organization
        );

        expect($invites)->toHaveCount(3);
        expect($invites[0]->email)->toBe('williamdoe@test.com');
        expect($invites[1]->email)->toBe('mariodoe@test.com');
        expect($invites[2]->email)->toBe('andredoe@test.com');
    });

    it('lists meetings with email sorting', function () {
        $invites = $this->service->list(
            organization: $this->organization,
            sortBy: 'email'
        );

        expect($invites)->toHaveCount(3);
        expect($invites[0]->email)->toBe('andredoe@test.com');
        expect($invites[1]->email)->toBe('mariodoe@test.com');
        expect($invites[2]->email)->toBe('williamdoe@test.com');
    });

    it('lists meetings with role sorting', function () {
        $invites = $this->service->list(
            organization: $this->organization,
            sortBy: 'role'
        );

        expect($invites)->toHaveCount(3);
        expect($invites[0]->email)->toBe('mariodoe@test.com');
        expect($invites[1]->email)->toBe('andredoe@test.com');
        expect($invites[2]->email)->toBe('williamdoe@test.com');
    });
});
