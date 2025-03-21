<?php

use App\Actions\Organization\CreateOrganization;
use App\DTO\Organization\CreateOrganizationDTO;
use App\Enums\ConfigEnum;
use App\Models\Config;
use App\Models\User;
use App\Services\ConfigService;
use App\Services\UserService;
use Illuminate\Support\Facades\Context;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->organization = (new CreateOrganization)($this->user, new CreateOrganizationDTO('New Organization'));
    Context::add('current_organization', $this->organization);

    $this->app->bind(UserService::class, function () {
        return new UserService($this->user);
    });

    // Instantiate UserService with a user
    $this->configService = app(ConfigService::class);

});

afterEach(function () {
    Mockery::close();
});

it('gets default value if organization does have it set', function () {
    expect(intval($this->configService->get(ConfigEnum::PAGINATION_ITEMS)))->toBe(15); // Default value
});

it('gets organization value if set', function () {
    Config::create([
        'organization_id' => $this->organization->id,
        'config_default_id' => ConfigEnum::PAGINATION_ITEMS,
        'value' => 10,
    ]);

    expect(intval($this->configService->get(ConfigEnum::PAGINATION_ITEMS)))->toBe(10); // Default value
});

it('call getTaskDefaultPriorityId for TASK_DEFAULT_PRIORITY_ID when company config is not set', function () {
    /** @var ConfigService */
    $this->mockConfigService = Mockery::mock(ConfigService::class)->makePartial();
    $config = $this->configService->getConfigWithDefaultByKey(ConfigEnum::TASK_DEFAULT_PRIORITY_ID);

    $this->mockConfigService
        ->shouldReceive('getTaskDefaultPriorityId')
        ->once()
        ->with($config->default);

    $this->mockConfigService->valueOrDefault(
        ConfigEnum::TASK_DEFAULT_PRIORITY_ID,
        $config
    );
});

it('call getTaskDefaultPriorityId for WORKSTREAM_DEFAULT_PRIORITY_ID when company config is not set', function () {
    /** @var ConfigService */
    $this->mockConfigService = Mockery::mock(ConfigService::class)->makePartial();
    $config = $this->configService->getConfigWithDefaultByKey(ConfigEnum::WORKSTREAM_DEFAULT_PRIORITY_ID);

    $this->mockConfigService
        ->shouldReceive('getTaskDefaultPriorityId')
        ->once()
        ->with($config->default);

    $this->mockConfigService->valueOrDefault(
        ConfigEnum::WORKSTREAM_DEFAULT_PRIORITY_ID,
        $config
    );
});

it('get first priority if default value is invalid', function () {
    $priorityid = $this->configService->getTaskDefaultPriorityId('anything invalid');
    $firstOrganizationPriorityId = $this->organization->priorities()->first()->id;

    expect($priorityid)->toBe($firstOrganizationPriorityId);
});
