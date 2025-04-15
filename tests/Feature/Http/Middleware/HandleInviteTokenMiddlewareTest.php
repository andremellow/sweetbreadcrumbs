<?php

use App\Handlers\RouteParameterHandler;
use App\Http\Middleware\HandleInviteTokenMiddleware;
use App\Http\Middleware\SetOrganizationRouteParameter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;

use function Pest\Laravel\actingAs;

covers(HandleInviteTokenMiddleware::class);

beforeEach(function () {
    [$user, $organization] = createOrganization();
    $this->user = $user;
});

afterEach(function () {
    Mockery::close();
});

it('calls HandleInviteTokenMiddleware', function () {
    actingAs($this->user);

    $response = $this->get(route('invite.accept', ['invite' => 'xxxxx']));

    $response->assertSessionHas('invite', 'xxxxx');

});


