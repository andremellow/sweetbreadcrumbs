<?php

use App\Handlers\RouteParameterHandler;
use App\Http\Middleware\SetOrganizationRouteParameter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;

use function Pest\Laravel\actingAs;

covers(SetOrganizationRouteParameter::class);

beforeEach(function () {
    [$user, $organization] = createOrganization();
    $this->user = $user;
    $this->organization = $organization;

    [$user, $anotherOrganization] = createOrganization($user, 'Another Organization');
    $this->anotherOrganization = $anotherOrganization;
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
