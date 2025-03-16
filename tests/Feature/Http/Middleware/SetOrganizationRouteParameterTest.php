<?php

use App\Actions\Organization\CreateOrganization;
use App\DTO\Organization\CreateOrganizationDTO;
use App\Http\Middleware\SetOrganizationRouteParameter;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;

use function Pest\Laravel\actingAs;

covers(SetOrganizationRouteParameter::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->organization = (new CreateOrganization)($this->user, new CreateOrganizationDTO('New Organization Name'));
    $this->anotherOrganization = (new CreateOrganization)($this->user, new CreateOrganizationDTO('Another Organization'));
});

afterEach(function () {
    Mockery::close();
});

it('sets URL and VIEW default when organization is present as string', function () {
    actingAs($this->user);
    $request = Request::create(route('workstreams.index', ['organization' => $this->anotherOrganization->slug]));

    // ✅ Create a Fake Route Object
    $fakeRoute = new class($this->anotherOrganization)
    {
        protected $parameters;

        public function __construct($organization)
        {
            $this->parameters = ['organization' => $organization->slug];
        }

        public function parameter($key, $default = null)
        {
            return $this->parameters[$key] ?? $default;
        }
    };

    $request->setRouteResolver(fn () => $fakeRoute);

    $next = function () {
        return response('This is a secret place');
    };

    // When
    $middleware = new SetOrganizationRouteParameter;
    $middleware->handle($request, $next);

    expect(View::shared('currentOrganizationSlug'))->toBe($this->anotherOrganization->slug);
    expect(URL::getDefaultParameters())->toHaveKey('organization', $this->anotherOrganization->slug);

});

it('sets URL and VIEW default when organization is present as Model', function () {
    actingAs($this->user);
    $request = Request::create(route('workstreams.index', ['organization' => $this->anotherOrganization->slug]));

    // ✅ Create a Fake Route Object
    $fakeRoute = new class($this->anotherOrganization)
    {
        protected $parameters;

        public function __construct($organization)
        {
            $this->parameters = ['organization' => $organization];
        }

        public function parameter($key, $default = null)
        {
            return $this->parameters[$key] ?? $default;
        }
    };

    $request->setRouteResolver(fn () => $fakeRoute);

    $next = function () {
        return response('This is a secret place');
    };

    // When
    $middleware = new SetOrganizationRouteParameter;
    $middleware->handle($request, $next);

    expect(View::shared('currentOrganizationSlug'))->toBe($this->anotherOrganization->slug);
    expect(URL::getDefaultParameters())->toHaveKey('organization', $this->anotherOrganization->slug);

});

it('sets URL and VIEW default when organization is NOT present', function () {
    actingAs($this->user);
    $request = Request::create(route('settings.profile'));

    $next = function () {
        return response('This is a secret place');
    };

    // When
    $middleware = new SetOrganizationRouteParameter;
    $response = $middleware->handle($request, $next);

    expect(View::shared('currentOrganizationSlug'))->toBe($this->organization->slug);
    expect(URL::getDefaultParameters())->toHaveKey('organization', $this->organization->slug);

});
