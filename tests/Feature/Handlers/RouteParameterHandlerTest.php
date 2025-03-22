<?php

use App\Actions\Organization\CreateOrganization;
use App\DTO\Organization\CreateOrganizationDTO;
use App\Handlers\RouteParameterHandler;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Context;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpKernel\Exception\HttpException;

use function Pest\Laravel\actingAs;

covers(RouteParameterHandler::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->organization = (new CreateOrganization)($this->user, new CreateOrganizationDTO('New Organization Name'));
    $this->organization1 = (new CreateOrganization)($this->user, new CreateOrganizationDTO('Another Organization'));
    $this->organizationFromOtherUser = (new CreateOrganization)(User::factory()->create(), new CreateOrganizationDTO('Another Organization'));

});

afterEach(function () {
    Mockery::close();
});

it('sets organization into session based on the slug', function () {
    actingAs($this->user);

    $request =
        Request::create(route('workstreams.index', ['organization' => $this->organization->slug]))
            ->setRouteResolver(fn () => new FakeRoute($this->organization->slug));

    $startSession = app(StartSession::class);
    $startSession->handle($request, fn ($return) => new Response);

    $routeParameterHandler = new RouteParameterHandler($request);
    $routeParameterHandler->handle();

    expect($request->session()->has('current_organization'))->toBe(true);
    expect($request->session()->get('current_organization')->id)->toBe($this->organization->id);

    expect(View::shared('currentOrganizationSlug'))->toBe($this->organization->slug);
    expect(URL::getDefaultParameters())->toHaveKey('organization', $this->organization->slug);
    expect(Context::get('current_organization')->slug)->toBe($this->organization->slug);
});

it('extracts the slug from Organization object', function () {
    actingAs($this->user);

    $request =
        Request::create(route('workstreams.index', ['organization' => $this->organization->slug]))
            ->setRouteResolver(fn () => new FakeRoute($this->organization));

    $startSession = app(StartSession::class);
    $startSession->handle($request, fn ($return) => new Response);

    $routeParameterHandler = new RouteParameterHandler($request);
    $routeParameterHandler->handle();

    expect($request->session()->has('current_organization'))->toBe(true);
    expect($request->session()->get('current_organization')->id)->toBe($this->organization->id);

    expect(View::shared('currentOrganizationSlug'))->toBe($this->organization->slug);
    expect(URL::getDefaultParameters())->toHaveKey('organization', $this->organization->slug);
    expect(Context::get('current_organization')->slug)->toBe($this->organization->slug);
});

it('user existing session when slug is null', function () {
    actingAs($this->user);

    $request =
        Request::create(route('workstreams.index', ['organization' => $this->organization->slug]))
            ->setRouteResolver(fn () => new FakeRoute);

    $startSession = app(StartSession::class);
    $startSession->handle($request, fn ($return) => new Response);

    $request->session()->put('current_organization', $this->organization);

    $routeParameterHandler = new RouteParameterHandler($request);
    $routeParameterHandler->handle();

    expect($request->session()->has('current_organization'))->toBe(true);
    expect($request->session()->get('current_organization')->id)->toBe($this->organization->id);

    expect(View::shared('currentOrganizationSlug'))->toBe($this->organization->slug);
    expect(URL::getDefaultParameters())->toHaveKey('organization', $this->organization->slug);
    expect(Context::get('current_organization')->slug)->toBe($this->organization->slug);
});

it('keeps the session if slug did not change', function () {
    actingAs($this->user);

    $request =
        Request::create(route('workstreams.index', ['organization' => $this->organization->slug]))
            ->setRouteResolver(fn () => new FakeRoute($this->organization->slug));

    $startSession = app(StartSession::class);
    $startSession->handle($request, fn ($return) => new Response);

    $request->session()->put('current_organization', $this->organization);

    $routeParameterHandler = new RouteParameterHandler($request);
    $routeParameterHandler->handle();

    expect($request->session()->has('current_organization'))->toBe(true);
    expect($request->session()->get('current_organization')->id)->toBe($this->organization->id);

    expect(View::shared('currentOrganizationSlug'))->toBe($this->organization->slug);
    expect(URL::getDefaultParameters())->toHaveKey('organization', $this->organization->slug);
    expect(Context::get('current_organization')->slug)->toBe($this->organization->slug);
});

it('throws 403 if not session and no slug', function () {
    actingAs($this->user);

    $request =
    Request::create(route('welcome.profile'))
        ->setRouteResolver(fn () => new FakeRoute);

    $startSession = app(StartSession::class);
    $startSession->handle($request, fn ($return) => new Response);

    $routeParameterHandler = new RouteParameterHandler($request);
    $routeParameterHandler->handle();
})->throws(HttpException::class, 'Invalid organization');

it('throws 403 if user do not have access to the organization', function () {
    actingAs($this->user);

    $request = Request::create(route('workstreams.index', ['organization' => $this->organizationFromOtherUser->slug]))
        ->setRouteResolver(fn () => new FakeRoute($this->organizationFromOtherUser->slug));

    $startSession = app(StartSession::class);
    $startSession->handle($request, fn ($return) => new Response);

    $routeParameterHandler = new RouteParameterHandler($request);
    $routeParameterHandler->handle();
})->throws(HttpException::class, 'You don\'t have access to this organization');

it('slips for livewire updates', function () {
    actingAs($this->user);

    $request = Request::create(route('livewire.update'), 'POST')
        ->setRouteResolver(fn () => new FakeRoute);

    Route::dispatchToRoute($request);

    $startSession = app(StartSession::class);
    $startSession->handle($request, fn ($return) => new Response);

    $routeParameterHandler = new RouteParameterHandler($request);

    expect($routeParameterHandler->shouldSkip())->toBe(true);
});
