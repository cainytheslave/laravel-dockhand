<?php

use Cainy\Dockhand\Events\EventAction;
use Cainy\Dockhand\Facades\TokenService;
use Illuminate\Http\Request;
use Lcobucci\JWT\Validation\Constraint\HasClaimWithValue;

Route::post(config('dockhand.notifications.route'), function (Request $request) {
    if ($request->bearerToken() === null) {
        abort(401, 'Unauthorized: Bearer token required');
    }

    if (!TokenService::validateToken($request->bearerToken(), function ($validator, $token) {
        $validator->assert($token, new HasClaimWithValue('access', 'notify'));
    })) {
        abort(401, 'Unauthorized: Bearer token invalid');
    }

    if (!$request->has('events') || empty($request->get('events'))) {
        abort(400, 'Bad Request: No events provided');
    }

    foreach ($request->events as $event) {
        switch ($event['action']) {
            case EventAction::PULL:
                Log::channel('stderr')->info('Pull event received');
                break;
            case EventAction::PUSH:
                Log::channel('stderr')->info('Push event received');
                break;
            case EventAction::MOUNT:
                Log::channel('stderr')->info('Mount event received');
                break;
            case EventAction::DELETE:
                Log::channel('stderr')->info('Delete event received');
                break;
            default:
                Log::channel('stderr')->error('Unknown event action');
                abort(400, 'Bad Request: Unknown event action');
        }
        
        Log::channel('stderr')->error(json_encode($event, JSON_PRETTY_PRINT));
    }
});
