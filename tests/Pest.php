<?php

use App\Actions\Organization\CreateOrganization;
use App\DTO\Organization\CreateOrganizationDTO;
use App\Models\Organization;
use App\Models\User;
use App\Services\OrganizationService;

use function Pest\Laravel\actingAs;

pest()->extend(Tests\DuskTestCase::class)
    ->use(Illuminate\Foundation\Testing\DatabaseMigrations::class)
    ->in('Browser');

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind a different classes or traits.
|
*/

pest()->extend(Tests\TestCase::class)
    ->use(Illuminate\Foundation\Testing\RefreshDatabase::class)
    ->in('Feature');

// Seed the database before each test

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function createOrganization(?User $user = null, string $name = 'New Organization Name')
{
    if (! $user) {
        $user = User::factory()->create();
    }
    actingAs($user);

    $organization = (new CreateOrganization)(
        $user,
        new CreateOrganizationDTO($name),
        app(OrganizationService::class)
    );

    return [$user, $organization];
}

function createUser()
{
    return User::factory()->create();
}

class FakeRoute
{
    protected $parameters;

    public function __construct(Organization|string|null $organization = null)
    {
        if ($organization) {
            $this->parameters = ['organization' => $organization];
        }
    }

    public function parameter($key, $default = null)
    {
        return $this->parameters[$key] ?? $default;
    }
}
