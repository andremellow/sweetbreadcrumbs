<?php

use App\Actions\Organization\CreateOrganization;
use App\DTO\Organization\CreateOrganizationDTO;
use App\Handlers\RouteParameterHandler;
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

it('calls RouteParameterHandler', function () {
    actingAs($this->user);
    $request = Request::create(route('workstreams.index', ['organization' => $this->anotherOrganization->slug]));

    // ✅ Create a Fake Route Object
    $fakeRoute = new FakeRoute($this->anotherOrganization->slug);
    $request->setRouteResolver(fn () => $fakeRoute);

    $next = function () {
        return response('This is a secret place');
    };

    $routeParameterHandler = $this->mock(RouteParameterHandler::class);

    $routeParameterHandler->shouldReceive('shouldSkip')->once()->andReturn(false);
    $routeParameterHandler->shouldReceive('handle')->once();

    $middleware = new SetOrganizationRouteParameter($routeParameterHandler);
    $middleware->handle($request, $next);

    // expect(View::shared('currentOrganizationSlug'))->toBe($this->anotherOrganization->slug);
    // expect(URL::getDefaultParameters())->toHaveKey('organization', $this->anotherOrganization->slug);

});

it('ingnores handle when skip', function () {
    actingAs($this->user);
    $request = Request::create(route('workstreams.index', ['organization' => $this->anotherOrganization->slug]));

    // ✅ Create a Fake Route Object
    $fakeRoute = new FakeRoute($this->anotherOrganization->slug);
    $request->setRouteResolver(fn () => $fakeRoute);

    $next = function () {
        return response('This is a secret place');
    };

    $routeParameterHandler = $this->mock(RouteParameterHandler::class);

    $routeParameterHandler->shouldReceive('shouldSkip')->once()->andReturn(true);
    $routeParameterHandler->shouldNotReceive('handle');

    $middleware = new SetOrganizationRouteParameter($routeParameterHandler);
    $middleware->handle($request, $next);

    // expect(View::shared('currentOrganizationSlug'))->toBe($this->anotherOrganization->slug);
    // expect(URL::getDefaultParameters())->toHaveKey('organization', $this->anotherOrganization->slug);

});
